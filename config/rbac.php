<?php

return [
    'roles' => [
        'supervisor' => [
            'display_name' => 'Supervisor',
            'description' => 'Manages all actions and resources, including system settings and public meetings.',
            'permissions' => [
                'user.*',
                'video.*',
                'course.*',
                'meeting.*',
                'system.*',
            ],
        ],
        'instructor' => [
            'display_name' => 'Instructor',
            'description' => 'Manages courses and private meetings for enrolled students.',
            'permissions' => [
                'course.create',
                'course.update',
                'course.delete',
                'course.manage',
                'meeting.private.manage',
                'video.upload',
            ],
        ],
        'editor' => [
            'display_name' => 'Editor',
            'description' => 'Manages video content for the platform.',
            'permissions' => [
                'video.manage',
                'video.upload',
                'video.update',
                'video.delete',
            ],
        ],
        'regular' => [
            'display_name' => 'Regular User',
            'description' => 'Standard user who can enroll in courses and join meetings.',
            'permissions' => [
                'course.enroll',
                'course.view',
                'video.view',
                'meeting.join',
            ],
        ],
    ],

    'permissions' => [
        'user' => ['create', 'read', 'update', 'delete', 'manage'],
        'video' => ['create', 'read', 'update', 'delete', 'manage', 'upload', 'view'],
        'course' => ['create', 'read', 'update', 'delete', 'manage', 'enroll', 'view'],
        'meeting' => ['create', 'read', 'update', 'delete', 'manage', 'join', 'private.manage'],
        'system' => ['manage', 'settings'],
    ],
];
