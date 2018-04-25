<?php

namespace Api\User\V1\Rest\User;

use Api\User\V1\Lib\Current\Converter\CurrentUserDtoConverter;
use Api\User\V1\Lib\Current\Dto\CurrentUserDto;
use Application\Util\ProblemResponseTrait;
use Application\Util\UserIdentityTrait;
use User\Entity\User;
use User\Model\UserModel;
use Util\Logger\PPLog;
use Zend\Http\Response;
use Zend\Hydrator\HydratorInterface;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

/**
 * Class UserResource
 *
 * @package Api\User\V1\Rest\User
 */
class UserResource extends AbstractResourceListener
{
    use ProblemResponseTrait,
        UserIdentityTrait;

    /**
     * @var UserModel
     */
    protected $model;

    /**
     * @var CurrentUserDtoConverter
     */
    private $converter;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var User
     */
    protected $currentUser;

    /**
     * UserResource constructor.
     *
     * @param UserModel               $model
     * @param CurrentUserDtoConverter $converter
     * @param HydratorInterface       $hydrator
     */
    public function __construct(UserModel $model, CurrentUserDtoConverter $converter, HydratorInterface $hydrator)
    {
        $this->model     = $model;
        $this->converter = $converter;
        $this->hydrator  = $hydrator;
    }

    /**
     * @param ResourceEvent $event
     *
     * @return mixed|ApiProblemResponse
     */
    public function dispatch(ResourceEvent $event)
    {
        $this->currentUser = $this->extractUser($event->getIdentity());
        if (empty($this->currentUser)) {
            return $this->problemResponse(Response::STATUS_CODE_403, 'Non personalized requests are forbidden');
        }

        return parent::dispatch($event);
    }

    /**
     * @param array $params
     *
     * @return CurrentUserDto
     */
    public function fetchAll($params = [])
    {
        return $this->converter->convert($this->currentUser);
    }

    /**
     * @param mixed $id
     * @param mixed $data
     *
     * @return CurrentUserDto|ApiProblemResponse
     */
    public function update($id, $data)
    {
        try {
            $data = $this->getInputFilter()->getValues();
            $this->hydrator->hydrate($data, $this->currentUser);
            $this->model->save($this->currentUser);

            return $this->converter->convert($this->currentUser);
        } catch (\Exception $exception) {
            PPLog::exception($exception);

            return $this->problemResponse(Response::STATUS_CODE_500, 'Error occurred during update user');
        }
    }

}
