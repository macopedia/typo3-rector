<?php

declare(strict_types=1);

use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\Rector\Annotation\IgnoreValidationAnnotationRector;
use Ssch\TYPO3Rector\Rector\Annotation\InjectAnnotationRector;
use Ssch\TYPO3Rector\Rector\Annotation\ReplaceAnnotationRector;
use Ssch\TYPO3Rector\Rector\Annotation\ValidateAnnotationRector;
use Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector;
use Ssch\TYPO3Rector\Rector\Core\Environment\RenameMethodCallToEnvironmentMethodCallRector;
use Ssch\TYPO3Rector\Rector\Core\Package\UsePackageManagerActivePackagesRector;
use Ssch\TYPO3Rector\Rector\Extbase\ConfigurationManagerAddControllerConfigurationMethodRector;
use Ssch\TYPO3Rector\Rector\Extbase\RemoveFlushCachesRector;
use Ssch\TYPO3Rector\Rector\Extbase\RemoveInternalAnnotationRector;
use Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethodRector;
use Ssch\TYPO3Rector\Rector\Frontend\Page\RemoveInitMethodFromPageRepositoryRector;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Rector\SymfonyPhpConfig\inline_value_objects;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(MoveRenderArgumentsToInitializeArgumentsMethodRector::class);

    $services->set(StringClassNameToClassConstantRector::class);

    $services->set(InjectAnnotationRector::class);

    $services->set(IgnoreValidationAnnotationRector::class);

    $services->set(ReplaceAnnotationRector::class)
        ->call('configure', [[
            ReplaceAnnotationRector::OLD_TO_NEW_ANNOTATIONS => [
                'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
                'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
                'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
            ],
        ]]);

    $services->set(ValidateAnnotationRector::class);

    $services->set(ConstantToEnvironmentCallRector::class);

    $services->set(UsePackageManagerActivePackagesRector::class);

    $services->set(RenameMethodCallToEnvironmentMethodCallRector::class);

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                new MethodCallRename('TYPO3\CMS\Core\Resource\ResourceStorage', 'dumpFileContents', 'streamFile'),
            ]),
        ]]);

    $services->set(RemoveFlushCachesRector::class);

    $services->set(RemoveInternalAnnotationRector::class);

    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [[
            RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [__DIR__ . '/../Migrations/Fluid/ClassAliasMap.php'],
        ]]);

    $services->set(ConfigurationManagerAddControllerConfigurationMethodRector::class);

    $services->set(RemoveInitMethodFromPageRepositoryRector::class);
};
