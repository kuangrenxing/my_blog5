<?php

// this contains the application parameters that can be maintained via GUI
return array(
    // avatar path
    'avatarPath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR,
    // file path
    'filePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'file'.DIRECTORY_SEPARATOR,
    // no avatar
    'noAvatar'=>'noavatar.png',
    // avatar width (resize)
    'avatarWidth'=>'64',
    // avatar height (resize)
    'avatarHeight'=>'64',
    // this is displayed in the header section
    'title'=>'My Yii Blog',
    // this is description
    'description'=>'Blog',
    // this is keywords
    'keywords'=>'Yii, demo, blog, download',
    // this is used in error pages
    'adminEmail'=>'462635006@qq.com',
    // number of posts displayed per page
    'postsPerPage'=>5,
    // whether post comments need to be approved before published
    'commentNeedApproval'=>true,
    // the copyright information displayed in the footer section
    'copyrightInfo'=>'Copyright &copy; 2009 by r0n9.GOL',
    // Image size in list of files
    'imageThumbnailBoundingBox'=>'80',
    // Registration confirm
    'confirmRegistration'=>false,
    // Email From
    'emailFrom'=>'My Yii Blog',
);
