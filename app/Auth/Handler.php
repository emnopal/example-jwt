<?php

namespace Badhabit\JwtLoginManagement\Auth;

use Badhabit\JwtLoginManagement\Domain\Decode;
use Badhabit\JwtLoginManagement\Domain\Encode;
use Badhabit\JwtLoginManagement\Helper\DotEnv;
use Badhabit\JwtLoginManagement\Model\DecodedSession;
use Badhabit\JwtLoginManagement\Model\EncodedSession;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

class Handler
{
    /**
     * Handling all the JWT actions
     * like encoding and decoding tokens
     *
     * Source from: https://www.w3jar.com/how-to-implement-the-jwt-with-php/
     */

    protected string $jwt_secret;
    protected array $token;
    protected int|float $issuedAt;
    protected int|float $expireAt;
    protected string $jwt;

    public function __construct(int|float $validity_time = (60 * 60 * 24))
    {

        // set default timezone
        date_default_timezone_set("Asia/Jakarta");
        $this->issuedAt = time();

        // token validity default for 1 day
        $this->expireAt = $this->issuedAt + $validity_time;

        /*
         * make sure your jwt_secret
         * is secure and make sure people
         * hard to guess or brute force
         * do not use key 'secret' in production
         * use a random string or hash of your jwt_secret
         * */

        // initialize the secret key on the dotenv file
        $dotenv = new DotEnv(__DIR__ . "/../../.env");
        $dotenv->load();

        // Set signature
        $this->jwt_secret = getenv('JWT_SECRET');

    }

    public function encode(Encode $encode): EncodedSession
    {

        /*
         * CAUTION:
         * Never store any credential or
         * sensitive information in the JWT
         * because it can be decoded by anyone
         * */

        $this->token = [
            // identifier to the token (who issued the token)
            'iss' => $encode->iss,
            'aud' => $encode->iss,

            // current timestamp to the token (when the token was issued)
            'iat' => $this->issuedAt,

            // token expiration time
            'exp' => $this->expireAt,

            // payload
            'data' => $encode->userSession
        ];

        $this->jwt = JWT::encode($this->token, $this->jwt_secret);

        $encodedSession = new EncodedSession();
        $encodedSession->key = $this->jwt;
        $encodedSession->expireAt = $this->expireAt;
        $encodedSession->issuedAt = $this->issuedAt;
        $encodedSession->data = $encode;

        return $encodedSession;
    }

    public function decode(Decode $decode): DecodedSession
    {
        try {
            $decoded = JWT::decode($decode->token, $this->jwt_secret, ['HS256']);
            $decodedSession = new DecodedSession();
            $decodedSession->payload = $decoded;

            return $decodedSession;
        } catch (ExpiredException $e) {
            throw new ExpiredException($e->getMessage());
        } catch (SignatureInvalidException|BeforeValidException $e) {
            throw new \Exception($e->getMessage());
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (\UnexpectedValueException $e) {
            throw new \UnexpectedValueException($e->getMessage());
        }

    }
}