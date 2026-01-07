<?php

namespace Lkt\Users\Config\Schemas;

use Lkt\Factory\Schemas\Fields\AssocJSONField;
use Lkt\Factory\Schemas\Fields\BooleanField;
use Lkt\Factory\Schemas\Fields\DateTimeField;
use Lkt\Factory\Schemas\Fields\ForeignKeyField;
use Lkt\Factory\Schemas\Fields\IdField;
use Lkt\Factory\Schemas\Fields\PivotField;
use Lkt\Factory\Schemas\Fields\PivotLeftIdField;
use Lkt\Factory\Schemas\Fields\PivotPositionField;
use Lkt\Factory\Schemas\Fields\PivotRightIdField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\InstanceSettings;
use Lkt\Factory\Schemas\Schema;
use Lkt\Menus\Instances\LktMenu;
use Lkt\Menus\Instances\LktMenuEntry;
use Lkt\Menus\Instances\LktMenuPivotEntry;
use Lkt\Users\Instances\LktUser;

Schema::add(
    Schema::table('lkt_menus', LktMenu::COMPONENT)
        ->setInstanceSettings(
            InstanceSettings::define(LktMenu::class)
                ->setNamespaceForGeneratedClass('Lkt\Menus\Generated')
                ->setWhereStoreGeneratedClass(__DIR__ . '/../../Generated')
        )
        ->setItemsPerPage(20)
        ->setCountableField('id')
        ->addField(IdField::define('id'))
        ->addField(
            DateTimeField::define('createdAt', 'created_at')
                ->setDefaultReadFormat('Y-m-d')
                ->setCurrentTimeStampAsDefaultValue()
        )
        ->addField(
            DateTimeField::define('updatedAt', 'updated_at')
                ->setDefaultReadFormat('Y-m-d')
                ->setCurrentTimeStampAsDefaultValue()
        )
        ->addField(ForeignKeyField::defineRelation(LktUser::COMPONENT, 'createdBy', 'created_by')->setDefaultValue([LktUser::class, 'getSignedInUserId']))
        ->addField(StringField::define('name')->setIsI18nJson())
        ->addField(AssocJSONField::define('nameData', 'name')->setIsI18nJson())
        ->addField(BooleanField::define('includeAvailableAdminRoutes', 'include_available_admin_routes'))
        ->addField(PivotField::definePivot(LktMenuEntry::COMPONENT, 'lkt_menus__entries', 'entries', 'menu_id')
            ->setPivotLeftIdField(PivotLeftIdField::defineRelation(LktMenu::COMPONENT, 'menu', 'menu_id'))
            ->setPivotRightIdField(PivotRightIdField::defineRelation(LktMenuEntry::COMPONENT, 'entry', 'entry_id'))
            ->setPivotPositionField(PivotPositionField::define('position'))
            ->setPivotInstanceConfig(LktMenuPivotEntry::class, 'Lkt\Menus\Generated', __DIR__ . '/../../Generated')
        )
        ->addAccessPolicy('write', ['nameData', 'includeAvailableAdminRoutes'])
);