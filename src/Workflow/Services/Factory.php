<?php
namespace Lavender\Workflow\Services;

use Illuminate\Support\Facades\App;
use Lavender\Support\Contracts\WorkflowInterface;

class Factory
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Session $session
     * @param Resolver $resolver
     * @param Validator $validator
     */
    public function __construct(Session $session, Resolver $resolver, Validator $validator)
    {
        $this->session  = $session;

        $this->resolver  = $resolver;

        $this->validator  = $validator;
    }

    /**
     * Get the evaluated view contents for the given workflow.
     *
     * @param  string $workflow
     * @return WorkflowInterface
     */
    public function make($workflow)
    {
        return App::make('workflow.view')->with('workflow', $workflow);
    }

    /**
     * @param WorkflowInterface $workflow
     * @return mixed
     */
    public function resolve(WorkflowInterface $workflow)
    {
        return $this->resolver->resolve($workflow);
    }

    /**
     * @param WorkflowInterface $workflow
     * @return mixed
     */
    public function find(WorkflowInterface $workflow)
    {
        return $this->session->find($workflow);
    }

    /**
     * @param $fields
     * @param $request
     */
    public function validate(array $fields, array $request)
    {
        return $this->validator->run($fields, $request);
    }

    public function next(WorkflowInterface $workflow)
    {
        return $this->session->next(
            $workflow->workflow,
            $workflow->state,
            $workflow->states
        );
    }


}