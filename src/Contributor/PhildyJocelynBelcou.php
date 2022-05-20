<?php
namespace Hamtaraws\App\Contributor;

use Hamtaraw\Contributor\AbstractContributor;

/**
 * @author Phil'dy Jocelyn Belcou <pj.belcou@gmail.com>
 */
class PhildyJocelynBelcou extends AbstractContributor
{
    /**
     * @inheritDoc
     * @see AbstractContributor::getName()
     */
    public function getName()
    {
        return "Phil'dy Jocelyn Belcou";
    }

    /**
     * @inheritDoc
     * @see AbstractContributor::getEmail()
     */
    public function getEmail()
    {
        return 'pj.belcou@gmail.com';
    }

    /**
     * @inheritDoc
     * @see AbstractContributor::getGithub()
     */
    public function getGithub()
    {
        return 'cejobelo';
    }
}