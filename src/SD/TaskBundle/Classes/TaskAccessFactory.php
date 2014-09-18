<?php

namespace SD\TaskBundle\Classes;

/**
 * AuthorTaskAccess class
 */
class TaskAccessFactory
{
    /**
     * @param Object       $em
     * @param TaskUserRole $taskUserRole
     *
     * @return AuthorTaskAccess|ControllerTaskAccess|PerformerTaskAccess|MatcherTaskAccess
     */
    public static function createAccess($em, $taskUserRole)
    {
        $stringAccess =$taskUserRole->getRole();

        if ($stringAccess == 'performer') {
            return new PerformerTaskAccess($em, $taskUserRole);
        } elseif ($stringAccess == 'author') {
            return new AuthorTaskAccess($em, $taskUserRole);
        } elseif ($stringAccess == 'controller') {
            return new ControllerTaskAccess($em, $taskUserRole);
        } elseif ($stringAccess == 'matcher') {
            return new MatcherTaskAccess($em, $taskUserRole);
        } else {
            return new BasicTaskAccess($em, $taskUserRole);
        }
    }
}
