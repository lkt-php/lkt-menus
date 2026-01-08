<?php

namespace Lkt\Menus\Instances;

use Lkt\Menus\Enums\MenuEntryType;
use Lkt\Menus\Generated\GeneratedLktMenu;
use Lkt\Users\Instances\LktUser;

class LktMenu extends GeneratedLktMenu
{
    const COMPONENT = 'lkt-menu';

    public function getNavigableEntries(): array
    {
        $haystack = $this->getEntries();
        $filtered = [];
        $user = LktUser::getSignedInUser();

        foreach ($haystack as $entry){
            switch ($entry->getType()) {
                case MenuEntryType::RelativeUrl->value:
                case MenuEntryType::FullUrl->value:

                    if ($entry->accessLevelIsOnlyAdminUsers()) {
                        if ($user->hasAdminAccess()) $filtered[] = $entry;
                    }
                    elseif ($entry->accessLevelIsOnlyLoggedUsers()) {
                        if ($user) $filtered[] = $entry;
                    } else {
                        $filtered[] = $entry;
                    }
                    break;

                case MenuEntryType::WebItems->value:
                    if (!$user) break;
                    if ($entry->accessLevelIsOnlyAdminUsers()) {
                        if ($user->hasAdminPermission($entry->getComponent(), 'ls')) $filtered[] = $entry;
                    }
                    elseif ($entry->accessLevelIsOnlyLoggedUsers()) {
                        if ($user->hasAdminPermission($entry->getComponent(), 'ls')) $filtered[] = $entry;
                    }
                    break;
            }
        }

        $r = [];
        foreach ($filtered as $entry) {
            $r[] = $entry->setAccessPolicy('r-app-menu')->autoRead();
        }
        return $r;
    }
}