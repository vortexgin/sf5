<?php


namespace App\Form;

use App\Helper\EntityTrait;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseForm extends AbstractType implements Saveable
{

    use EntityTrait;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Object serializer
     *
     * @var \Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    public function __construct(DocumentManager $dm, ValidatorInterface $validator)
    {
        $this->dm = $dm;
        $this->validator = $validator;

        $this->serializer = new Serializer([
            new DateTimeNormalizer(),
            new ObjectNormalizer(),
        ], [new JsonEncoder()]);

    }

    /**
     * @inheritDoc
     */
    public function save($params)
    {
        // TODO: Implement save() method.
    }
}