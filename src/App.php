<?php
namespace Hamtaraw\Microservice\App;

use Hamtaraw\AbstractMicroservice;
use Hamtaraw\Microservice\App\Contributor\PhildyJocelynBelcou;
use Hamtaraw\Microservice\App\Middleware\Router;

/**
 * Microservice definition.
 *
 * @author Phil'dy Jocelyn Belcou <pj.belcou@gmail.com>
 */
class App extends AbstractMicroservice
{
    /**
     * @inheritDoc
     * @see AbstractMicroservice::getContributors()
     */
    public function getContributors()
    {
        return [
            new PhildyJocelynBelcou,
        ];
    }

    /**
     * @inheritDoc
     * @see AbstractMicroservice::getComponents()
     */
    public function getComponents()
    {
        return [];
    }

    /**
     * @inheritDoc
     * @see AbstractMicroservice::getMiddlewares()
     */
    public function getMiddlewares()
    {
        return [
            Router::class,
        ];
    }
}