<?php
/**
 * Formulaire d'authentification | Entête.
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<?php
if ($infos = $this->getMessages('info')) :
    echo partial('notice', [
        'attrs'   => [
            'class' => '%s Signin-authNotices Signin-authInfos'
        ],
        'content' => $this->fetch('auth/infos', compact('infos')),
        'type'    => 'info'
    ]);
endif;
?>
<?php
if ($errors = $this->getMessages('error')) :
    echo partial('notice', [
        'attrs'   => [
            'class' => '%s Signin-authNotices Signin-authErrors'
        ],
        'content' => $this->fetch('auth/errors', compact('errors')),
        'type'    => 'error'
    ]);
endif;