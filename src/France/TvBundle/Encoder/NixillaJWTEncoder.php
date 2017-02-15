<?php
    /**
     * Created by PhpStorm.
     * User: mc
     * Date: 05/02/2017
     * Time: 00:22
     */

    namespace France\TvBundle\Encoder;


    use JWT\Authentication\JWT;
    use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

    /**
     * NixillaJWTEncoder
     *
     * @author Nicolas Cabot <n.cabot@lexik.fr>
     */
    class NixillaJWTEncoder implements JWTEncoderInterface
    {
        /**
         * @var string
         */
        protected $key;

        /**
         * __construct
         */
        public function __construct($key = 'test')
        {
            $this->key = $key;
        }

        /**
         * {@inheritdoc}
         */
        public function encode(array $data)
        {
            return JWT::encode($data, $this->key);
        }

        /**
         * {@inheritdoc}
         */
        public function decode($token)
        {
            try {
                return (array)JWT::decode($token, $this->key);
            } catch (\Exception $e) {
                return false;
            }
        }
    }