export default [
    ...applyRules(['guest'], [
        {
            path: '', component: require('$comp/guest/GuestWrapper').default, children: [
                {path: '/', name: 'films', component: require('$comp/guest/films/index').default},
                {path: '/films/:slug', name: 'films-detail', component: require('$comp/guest/films/details').default},
            ]
        },
        {
            path: '', component: require('$comp/auth/AuthWrapper').default, redirect: {name: 'login'}, children:
                [
                    {path: '/login', name: 'login', component: require('$comp/auth/login/Login').default},
                    {path: '/register', name: 'register', component: require('$comp/auth/register/Register').default},
                    {
                        path: '/password', component: require('$comp/auth/password/PasswordWrapper').default, children:
                            [
                                {
                                    path: '',
                                    name: 'forgot',
                                    component: require('$comp/auth/password/password-forgot/PasswordForgot').default
                                },
                                {
                                    path: 'reset/:token',
                                    name: 'reset',
                                    component: require('$comp/auth/password/password-reset/PasswordReset').default
                                }
                            ]
                    }
                ]
        },
    ]),
    ...applyRules(['auth'], [
        {
            path: '', component: require('$comp/admin/AdminWrapper').default, children: [
                {path: '/user/films', name: 'films-user', component: require('$comp/guest/films/index').default},
                {
                    path: '/user/films/:slug',
                    name: 'films-detail-user',
                    component: require('$comp/guest/films/details').default
                },
            ]
        },
        {
            path: '', component: require('$comp/admin/AdminWrapper').default, children:
                [
                    {path: '', name: 'index', redirect: {name: 'profile'}},
                    {
                        path: 'profile', component: require('$comp/admin/profile/ProfileWrapper').default, children:
                            [
                                {path: '', name: 'profile', component: require('$comp/admin/profile/Profile').default},
                                {
                                    path: 'edit',
                                    name: 'profile-edit',
                                    component: require('$comp/admin/profile/edit/ProfileEdit').default
                                }
                            ]
                    },
                    {
                        path: '',
                        component: require('$comp/common/FullContentWidthWrapper').default,
                        children: [
                            {
                                path: 'film',
                                component: require('$comp/common/CreateFullWidthContentWrapper').default,
                                children: [
                                    {
                                        path: 'create',
                                        name: 'create-film',
                                        component: require('$comp/admin/film/Create').default
                                    }
                                ]
                            }
                        ]
                    }
                ]
        }
    ]),
    {path: '*', redirect: {name: 'index'}}
]

function applyRules(rules, routes) {
    for (let i in routes) {
        routes[i].meta = routes[i].meta || {}

        if (!routes[i].meta.rules) {
            routes[i].meta.rules = []
        }
        routes[i].meta.rules.unshift(...rules)

        if (routes[i].children) {
            routes[i].children = applyRules(rules, routes[i].children)
        }
    }

    return routes
}
