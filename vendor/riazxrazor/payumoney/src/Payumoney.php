<?php

namespace Riazxrazor\Payumoney;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Payumoney
{
    const TEST_URL = 'https://test.payu.in/_payment';

    const PRODUCTION_URL = 'https://secure.payu.in/_payment';

    /**
     * @var string
     */
    private $KEY;

    /**
     * @var string
     */
    private $SALT;

    /**
     * @var bool
     */
    private $TEST_MODE;

    /**
     * @var bool
     */
    private $DEBUG;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $resolver = (new OptionsResolver())
            ->setDefaults(['TEST_MODE' => TRUE,'DEBUG' => FALSE])
            ->setRequired(['KEY', 'SALT', 'TEST_MODE'])
            ->setAllowedTypes('KEY', 'string')
            ->setAllowedTypes('SALT', 'string')
            ->setAllowedTypes('TEST_MODE', 'bool')
            ->setAllowedTypes('DEBUG', 'bool');

        $options = $resolver->resolve($options);

        foreach ($options as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    /**
     * @return string
     */
    public function getMerchantKey()
    {
        return $this->KEY;
    }

    /**
     * @return string
     */
    public function getMerchantSalt()
    {
        return $this->SALT;
    }

    /**
     * @return bool
     */
    public function getTestMode()
    {
        return $this->TEST_MODE;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->DEBUG;
    }

    /**
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->TEST_MODE ? self::TEST_URL : self::PRODUCTION_URL;
    }

    /**
     * @return array
     */
    public function getChecksumParams()
    {
        return array_merge(
            ['txnid', 'amount', 'productinfo', 'firstname', 'email'],
            array_map(function ($i) {
                return "udf{$i}";
            }, range(1, 10))
        );
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function getChecksum(array $params)
    {
        $values = array_map(
            function ($field) use ($params) {
                return isset($params[$field]) ? $params[$field] : '';
            },
            $this->getChecksumParams()
        );

        $values = array_merge([$this->getMerchantKey()], $values, [$this->getMerchantSalt()]);

        return hash('sha512', implode('|', $values));
    }

    /**
     * @param array $params
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function pay(array $params)
    {
        $requiredParams = ['txnid', 'amount', 'firstname', 'email', 'phone', 'productinfo', 'surl', 'furl'];

        foreach ($requiredParams as $requiredParam) {
            if (!isset($params[$requiredParam])) {
                throw new \InvalidArgumentException(sprintf('"%s" is a required param.', $requiredParam));
            }
        }

        $params = array_merge($params, ['hash' => $this->getChecksum($params), 'key' => $this->getMerchantKey()]);
        $params = array_map(function ($param) {
            return htmlentities($param, ENT_QUOTES, 'UTF-8', false);
        }, $params);

        $output = sprintf('<form id="payment_form" method="POST" action="%s">', $this->getServiceUrl());

        foreach ($params as $key => $value) {
            $output .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, $value);
        }

        $output .= '<input type="hidden" name="service_provider" value="payu_paisa" size="64" />';

        $output .= '<div id="redirect_info" style="display: none">Redirecting...</div>
                <input id="payment_form_submit" type="submit" value="Proceed to PayUMoney" />
            </form>
            <script>
                document.getElementById(\'redirect_info\').style.display = \'block\';
                document.getElementById(\'payment_form_submit\').style.display = \'none\';
                document.getElementById(\'payment_form\').submit();
            </script>';

        return Response::create($output, 200, [
            'Content-type' => 'text/html; charset=utf-8',
        ]);
    }

    public function completePay(array $params)
    {
        return new PayumoneyResponse($this, $params);
    }
}
