<?php

/*
 * Use for formatting things.
 *
 * 1. Format a Value, in a Namespace
 *
 * Write a format method for a key, ie 'timestamp'.
 * Add specificity with a namespace ie 'cats'.
 *
 *  - call formatValueByKeyAndNameSpace('timestamp', '1482443673', 'cats');
 *
 *  - that calls a method you'd need to write: formatTimestampCats
 *
 *
 * 2. Add Formats of Value, in a Namespace
 *
 * Write a method to create an array of additional formats based on the value
 *
 * - call addFormatsByKeyAndNameSpace('timestamp', '1482443673', 'cats');
 *
 * - that calls a method you'd need to write: addFormatsTimestampCats()
 *
 * ----------------------------
 * See examples below!
 *
 */

namespace Bc\App\Apps\ExampleApp;

use Bc\App\Core\Services\FormatService;

class FormatExampleAppService extends FormatService {

    /***************************************************************************
     *
     * Write custom methods below:
     *
     **************************************************************************/

    /**
     * Format key 'TimeStamp' in NameSpace 'Example'
     * @param type $value
     */
    protected function formatTimestampExample($value, $excludeValues = [])
    {
        return date('Y-m-d H:i:s', $value);
    }

    protected function formatUser($user, $excludeValues = [])
    {
        $userObj = \Bc\App\Core\Util::objectifyArray($user);

        foreach ($excludeValues as $exclude) {
            if (!isset($userObj->{$exclude})) {
                continue;
            }

            unset($userObj->{$exclude});
        }

        return $userObj;
    }

//    protected function addFormatsDecisionDecision($value, $excludeValues = [])
//    {
//        $statusTypes = $this->route->getSettings()->matchStatusTypes;
//
//        return [
//            'status' => $value,
//            'status_id' => $statusTypes->{$value}->id,
//        ];
//    }
//
//    protected function addFormatsExternalDecision($value, $excludeValues = [])
//    {
//        $newKeys = array_map(function($key){
//            return 'candidate_' . $key;
//        }, array_keys($value));
//
//        return array_combine($newKeys, $value);
//    }
//
//    protected function addFormatsCrDecision($value, $excludeValues = [])
//    {
//        $newKeys = array_map(function($key){
//            return 'listing_' . $key;
//        }, array_keys($value));
//
//        return array_combine($newKeys, $value);
//    }

}

