<?php

return [
    'roles' => [
        'supervisor' => [
            [
                'label' => 'Dashboard',
                'icon' => 'LayoutDashboard',
                'route' => 'dashboard',
            ],
            [
                'label' => 'Analytics',
                'icon' => 'BarChart3',
                'route' => 'reports.index',
            ],
            [
                'label' => 'User Management',
                'icon' => 'Users',
                'children' => [
                    ['label' => 'View All Users', 'route' => 'admin.users.index'],
                    ['label' => 'Pending Invites', 'route' => 'admin.invites.index'],
                    ['label' => 'Send Invite', 'route' => 'admin.invite', 'method' => 'POST'],
                ],
            ],
            [
                'label' => 'Instructors',
                'icon' => 'GraduationCap',
                'route' => 'admin.instructors.index',
            ],
            [
                'label' => 'Content Oversight',
                'icon' => 'ShieldCheck',
                'children' => [
                    ['label' => 'Courses', 'route' => 'courses.index'],
                    ['label' => 'Videos', 'route' => 'videos.index'],
                ],
            ],
            [
                'label' => 'Meetings',
                'icon' => 'Video',
                'route' => 'meetings.index',
            ],
        ],

        'instructor' => [
            [
                'label' => 'My Courses',
                'icon' => 'BookOpen',
                'route' => 'courses.index',
            ],
            [
                'label' => 'Upload Video',
                'icon' => 'Video',
                'route' => 'videos.store', // Placeholder for upload modal trigger
            ],
            [
                'label' => 'Meetings',
                'icon' => 'MessageSquare',
                'route' => 'meetings.index',
            ],
            [
                'label' => 'My Reports',
                'icon' => 'PieChart',
                'route' => 'reports.index',
            ],
        ],

        'editor' => [
            [
                'label' => 'Video Library',
                'icon' => 'Film',
                'route' => 'videos.index',
            ],
            [
                'label' => 'Upload Content',
                'icon' => 'Upload',
                'route' => 'videos.store',
            ],
            [
                'label' => 'Activity Report',
                'icon' => 'FileText',
                'route' => 'reports.index',
            ],
        ],

        'regular' => [
            [
                'label' => 'My Learning',
                'icon' => 'Compass',
                'route' => 'courses.index',
            ],
            [
                'label' => 'Meetings',
                'icon' => 'Video',
                'route' => 'meetings.index',
            ],
            [
                'label' => 'My Progress',
                'icon' => 'TrendingUp',
                'route' => 'reports.index',
            ],
        ],
    ],

    'common' => [
        [
            'label' => 'Profile',
            'icon' => 'User',
            'route' => 'profile.index',
        ],
        [
            'label' => 'Settings',
            'icon' => 'Settings',
            'route' => 'settings.index',
        ],
    ],
];
