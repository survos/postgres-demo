<?php


namespace App\Service;

use App\Entity\AccessInterface;
use App\Entity\Core;
use App\Entity\Field\AttributeField;
use App\Entity\Field\CategoryField;
use App\Entity\Field\DatabaseField;
use App\Entity\Field\Field;
use App\Entity\Field\MeasurementField;
use App\Entity\Field\ReferenceField;
use App\Entity\Field\RelationField;
use App\Entity\ProjectInterface;
use App\Repository\CategoryRepository;
use App\Repository\InstanceRepository;
use App\Repository\RelationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Aws\map;

class AppService
{
    public const LANGUAGES = ['en','fr','es','ru','pt','de'];

}
