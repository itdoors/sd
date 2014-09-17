<?php

namespace SD\TaskBundle\Classes;

/**
 * AuthorTaskAccess class
 */
class TaskAccessFactory
{
    /**
     * @param string                      $stringAccess
     * @param \SD\TaskBundle\Entity\Stage $stage
     * @param bool                        $isViewed
     *
     * @return AuthorTaskAccess|ControllerTaskAccess|PerformerTaskAccess|MatcherTaskAccess
     */
    public static function createAccess($stringAccess, $stage, $isViewed)
    {
        if ($stringAccess == 'performer') {
            return new PerformerTaskAccess($stage, $isViewed);
        } elseif ($stringAccess == 'author') {
            return new AuthorTaskAccess($stage, $isViewed);
        } elseif ($stringAccess == 'controller') {
            return new ControllerTaskAccess($stage, $isViewed);
        } elseif ($stringAccess == 'matcher') {
            return new MatcherTaskAccess($stage, $isViewed);
        } else {
            return new BasicTaskAccess($stage, $isViewed);
        }
    }
}
