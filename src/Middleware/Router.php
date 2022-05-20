<?php
namespace Hamtaraws\App\Middleware;

use Exception;
use Hamtaraw\Component\AbstractForm;
use Hamtaraw\Component\AbstractModal;
use Hamtaraw\Component\AbstractPage;
use Hamtaraw\Middleware\AbstractAjax;
use Hamtaraw\Middleware\AbstractMiddleware;
use Hamtaraw\Middleware\InputConfig;

/**
 * Router middleware.
 *
 * @author Phil'dy Jocelyn Belcou <pj.belcou@gmail.com>
 */
class Router extends AbstractMiddleware
{
    /**
     * @inheritDoc
     * @see AbstractMiddleware::InputConfigs()
     */
    public function InputConfigs()
    {
        return [
            new InputConfig('mddl', 'string', true),
        ];
    }

    /**
     * @inheritDoc
     * @throws Exception
     * @see AbstractMiddleware::process()
     */
    public function process()
    {
        $this->checkRequest();

        if (array_key_exists('mddl', $this->aInputs))
        {
            $sMiddleware = str_replace('/', '\\', $this->aInputs['mddl']);
            preg_match('`.+\\\\(.+)$`', $sMiddleware, $aMatches);
            $sMiddleware = "$sMiddleware\\$aMatches[1]";

            if (!class_exists($sMiddleware))
            {
                return $this->Modules->Response()->getFailure("Unknown middleware : $sMiddleware")->sendAjax();
            }

            elseif (!$this->Microservice->isAllowed($sMiddleware))
            {
                return $this->Modules->Response()->getFailure("Unauthorized middleware : $sMiddleware")->sendAjax();
            }

            # Middleware instantiation
            /** @var AbstractMiddleware $Middleware */
            $Middleware = new $sMiddleware($this->Microservice, $this->Modules);

            # Loading a modal
            if ($Middleware instanceof AbstractModal)
            {
                if ($this->Microservice->showLog())
                {
                    error_log("Running modal {$Middleware->getNamespace()}");
                }

                return $this->Modules->Response()->getSuccess('Nickel')->setExtraParam('modal', $Middleware)->sendAjax();
            }

            # Running a form
            elseif ($Middleware instanceof AbstractForm)
            {
                if ($this->Microservice->showLog())
                {
                    error_log("Running form {$Middleware->getNamespace()}");
                }

                return $Middleware->executeAndGetResponse()->sendAjax();
            }

            # Running an ajax request
            elseif ($Middleware instanceof AbstractAjax)
            {
                if ($this->Microservice->showLog())
                {
                    error_log("Running ajax request {$Middleware->getNamespace()}");
                }

                return $Middleware->executeAndGetResponse()->sendAjax();
            }
        }

        # Loading a page
        $sRequestUri = $this->Modules->Request()->getRequestUri();

        /** @var AbstractPage[] $Pages */
        $Pages = [];
        foreach ($this->Microservices as $Microservice)
        {
            foreach ($Microservice->getComponents() as $sComponent)
            {
                if (preg_match('`\\\\Component\\\\Page`', $sComponent))
                {
                    $Pages[] = new $sComponent($Microservice, $this->Microservices);
                }
            }
        }

        foreach ($Pages as $Page)
        {
            if ($Page->isMatching($sRequestUri))
            {
                foreach ($Page->Urls() as $Url)
                {
                    if (preg_match($Url->getPattern(), $sRequestUri, $aMatches))
                    {
                        foreach ($Url->getKeys() as $sKey)
                        {
                            $this->aInputs[$sKey] = $aMatches[$sKey];
                        }
                    }
                }


                if ($this->Microservice->showLog())
                {
                    error_log("Running page {$Page::getId()}");
                    $Page->process();
                }

                $sView = $Page->getView();

                echo $sView;
            }
        }

        if ($this->Microservice->showLog())
        {
            error_log("Kelanight! Using Hamtaraw to build your web application is a good choice, congratulations to you.");
        }
    }
}