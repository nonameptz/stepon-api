<?php

namespace Api\User\V1\Rpc\Registration;

use Api\User\V1\Lib\Current\Converter\CurrentUserDtoConverter;
use Application\Service\MailService;
use Application\Util\InputFilterExtractorTrait;
use Application\Util\ProblemResponseTrait;
use User\Entity\User;
use User\Model\UserModel;
use Util\Logger\PPLog;
use Zend\Http\Response;
use Zend\Hydrator\HydratorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel;
use ZF\Hal\Entity;

/**
 * Class RegistrationController
 *
 * @package Api\User\V1\Rpc\Registration
 */
class RegistrationController extends AbstractActionController
{
    use ProblemResponseTrait, InputFilterExtractorTrait;

    /**
     * @var UserModel
     */
    private $model;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var CurrentUserDtoConverter
     */
    private $converter;

    /**
     * RegistrationController constructor.
     *
     * @param UserModel               $model
     * @param MailService             $mailService
     * @param HydratorInterface       $hydrator
     * @param CurrentUserDtoConverter $converter
     */
    public function __construct(
        UserModel $model,
        MailService $mailService,
        HydratorInterface $hydrator,
        CurrentUserDtoConverter $converter
    ) {
        $this->model       = $model;
        $this->mailService = $mailService;
        $this->hydrator    = $hydrator;
        $this->converter   = $converter;
    }

    /**
     * @return ApiProblemResponse|ViewModel
     */
    public function registrationAction()
    {
        try {
            $inputFilter = $this->getInputFilter($this->getEvent());
            $data        = $inputFilter->getValues();
            /** @var User $user */
            $user = $this->hydrator->hydrate($data, new User());
            $this->model->save($user);
            $this->mailService
                ->setSubject('Подтверждение регистрации')
                ->addTo($user->getUsername())
                ->assembleBody(
                    'user/mail/send-confirm-link',
                    [
                        'user' => $user,
                        'link' => $this->model->createConfirmLink($user),
                    ]
                );
            $this->mailService->send();

            return new ViewModel(
                [
                    'payload' => new Entity(
                        [
                            $this->converter->convert($user),
                        ]
                    ),
                ]
            );
        } catch (\Exception $exception) {
            PPLog::exception($exception);

            return $this->problemResponse(Response::STATUS_CODE_500, 'Error occurred during registration');
        }
    }
}
