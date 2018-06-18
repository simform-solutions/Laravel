<!DOCTYPE html>
{!!
    Html::tag(
        'html',
        [
            Html::tag(
                'head',
                [
                    Html::meta(null, null, ['charset' => 'utf-8']),
                    Html::meta(null, 'IE=edge', ['http-equiv' => 'X-UA-Compatible']),
                    Html::meta('viewport', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'),
                    Html::meta('csrf-token', csrf_token()),
                    Html::tag('title', [
                        __(env('APP_NAME')),
                        ' -' . $__env->yieldContent('title')
                    ]),
                    Html::favicon(env('APP_LOGO'), ['rel' => 'icon', 'type' => 'image/png']),
                    Html::style('https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext'),
                    Html::style('https://fonts.googleapis.com/icon?family=Material+Icons'),
                    Html::style('node_modules/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.min.css'),
                    Html::style('node_modules/adminbsb-materialdesign/plugins/node-waves/waves.min.css'),
                    Html::style('node_modules/adminbsb-materialdesign/plugins/animate-css/animate.min.css'),
                    Html::style('node_modules/adminbsb-materialdesign/plugins/sweetalert/sweetalert.css'),
                    Html::style('node_modules/adminbsb-materialdesign/css/style.min.css'),
                    Html::tag('style', ['
                        .submit-spinner-layer {
                            border-color: inherit !important;
                        }
                    '], ['media' => 'all', 'type' => 'text/css']),
                    $__env->yieldPushContent('styles'),
                    "<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                    <!--[if lt IE 9]>",
                    Html::script('https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'),
                    Html::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js'),
                    "<![endif]-->"
                ]
            ),
            Html::tag(
                'body',
                [
                    $__env->yieldContent('content'),
                    Html::script('node_modules/adminbsb-materialdesign/plugins/jquery/jquery.min.js'),
                    Html::script('node_modules/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.min.js'),
                    Html::script('node_modules/adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.min.js'),
                    Html::script('node_modules/adminbsb-materialdesign/plugins/node-waves/waves.min.js'),
                    Html::script('node_modules/adminbsb-materialdesign/plugins/sweetalert/sweetalert.min.js'),
                    Html::script('node_modules/adminbsb-materialdesign/js/admin.js'),
                    Html::script('js/app.js'),
                    $__env->yieldPushContent('scripts')
                ],
                [
                    'class' => $__env->yieldContent('body_classes')
                ]
            )
        ],
        [
            'lang' => app()->getLocale()
        ]
    )
!!}
