<?php

namespace Lkt\Users\Config\Schemas;

use Lkt\Factory\Schemas\Fields\AssocJSONField;
use Lkt\Factory\Schemas\Fields\DateTimeField;
use Lkt\Factory\Schemas\Fields\IdField;
use Lkt\Factory\Schemas\Fields\IntegerChoiceField;
use Lkt\Factory\Schemas\Fields\IntegerField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\InstanceSettings;
use Lkt\Factory\Schemas\Schema;
use Lkt\Menus\Enums\MenuEntryType;
use Lkt\Menus\Instances\LktMenuEntry;

Schema::add(
    Schema::table('lkt_menus_entries', LktMenuEntry::COMPONENT)
        ->setInstanceSettings(
            InstanceSettings::define(LktMenuEntry::class)
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
        ->addField(StringField::define('name')->setIsI18nJson())
        ->addField(AssocJSONField::define('nameData', 'name')->setIsI18nJson())
        ->addField(IntegerChoiceField::enumChoice(MenuEntryType::class, 'type'))
        ->addField(StringField::define('component'))
        ->addField(StringField::define('url'))
        ->addField(IntegerField::define('itemId', 'item_id'))
        ->addAccessPolicy('write', ['nameData', 'includeAvailableAdminRoutes'])
);