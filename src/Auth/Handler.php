<?php

namespace Badhabit\JwtLoginManagement\Auth;

require __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;

class Handler
{
    /**
     * Handling all the JWT actions
     * like encoding and decoding tokens
     */

    protected string $jwt_secret;
    protected array $token;
    protected int|float $issuedAt;
    protected int|float $expireAt;
    protected string $jwt;

    public function __construct(int|float $validity_time = (60*60))
    {
        // set default timezone
        date_default_timezone_set("Asia/Jakarta");
        $this->issuedAt = time();

        // token validity default for 1 hour
        $this->expireAt = $this->issuedAt + $validity_time;

        // set signature
        $this->jwt_secret = 'secret';
    }

    public static function readableTimestamp(float|int|string $timestamp): string
    {
        $readable = \DateTime::createFromFormat('U', $timestamp);
        $readable->setTimezone(new \DateTimeZone("Asia/Jakarta"));
        $readable->format('Y-m-d H:i:s');
        $readable = (array)$readable;
        return $readable['date'];
    }

    public function encode(string $iss, array $data): array|string
    {

        /*
         * CAUTION:
         * Never store any credential or
         * sensitive information in the JWT
         * because it can be decoded by anyone
         * */

        $this->token = [
            // identifier to the token (who issued the token)
            'iss' => $iss,
            'aud' => $iss,

            // current timestamp to the token (when the token was issued)
            'iat' => $this->issuedAt,

            // token expiration time
            'exp' => $this->expireAt,

            // payload
            'data' => $data
        ];

        try {
            $this->jwt = JWT::encode($this->token, $this->jwt_secret);

            return [
                'issued' => self::readableTimestamp($this->issuedAt),
                'expire' => self::readableTimestamp($this->expireAt),
                'key' => $this->jwt
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function decode(string $jwt_token): array|string
    {
        try {
            $decode = JWT::decode($jwt_token, $this->jwt_secret, ['HS256']);
            return [
                'issued' => self::readableTimestamp((string)$decode->iat),
                'expire' => self::readableTimestamp((string)$decode->exp),
                'data' => (array)$decode->data
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}