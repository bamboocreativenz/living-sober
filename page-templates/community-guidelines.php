<?php
/**
 * Template Name: Community guidelines
 *
 * @package kleo-ls
 * @since August 2017
 */

/**
 * 
 */
$quotes = [
    "seizetheday" => [
        "quote" => "This site with all the wonderful brave souls on it, is very dear to my heart and I know that without the support from all the people on here, I would not have been able to leave alcohol addiction behind. Together we are stronger than we know!"
    ],
    "JM" => [
        "quote" => "This site is important to me. I love its non-hierarchical structure, the support and kindness people consistently show each other, and the comfort people have about sharing a wide range of concerns. I have turned to the site when I've been stressed out by family, work, changing friendships, and everyday life, as well as when I've been exhilarated by sobriety and its possibilities, and have felt understood and supported."
    ],
    "behind-the-sofa" => [
        "quote" => "I really want Living Sober to continue as it helps with my recovery. I'm keen to offer encouragement to new people as I've been in that position and I know what it's like ."
    ],
    "morgan" => [
        "quote" => "Three years of not drinking has made me realise I never want to go back to that unhealthy (physical and mental) lifestyle. I would never have done it without the support of Lotta and Living Sober."
    ],
    "temperance" => [
        "quote" => "I love what has been created in Living Sober and I'd love to be able to help keep it going with the same cultural feel that it has today. I've found that service keeps me out of my own head and it turns out that having responsibilities like this, which I was afraid of for a long time, actually help me."
    ],
    "janet" => [
        "quote" => "Without this site to turn to I would have felt so alone in my days of early recovery. It gave me a sense of belonging and helped reinforce my decision to stop drinking. It will be good to give back to the community :)."
    ]
];

$tmp = [];
foreach ($quotes as $username => $attribs) {
    
    # Could also be `user_login` or `display_name`, `user_nicename`
    $user = get_user_by("login", $username);
    $attribs["user"] = $user;
    $tmp[$username] = $attribs;
}
$quotes = $tmp;

# Load up data for Lotta
$lotta = get_user_by("login", "Mrs D");
$lottaData = $lotta->data;
$lottaProfileLink = bp_core_get_user_domain($lotta->ID);
$lottaRegisteredDate = DateTime::createFromFormat("Y-m-d H:i:s", $lottaData->user_registered);
$lottaAvatar = bp_core_fetch_avatar([
    'item_id' => $lotta->ID, 
    'type' => 'thumb'
]);

get_header();
?>


<div id="main-content" class="main-content">

    <div id="primary">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-2 blogMenu">
                    
                    

                    <?php $user = wp_get_current_user(); ?>

                    <aside class="widget widget_recent_entries">		
                        <h4 class="widget-title">Community area</h4>		
                        <ul>
                            <?php if ( is_user_logged_in() ) : ?>
                            <li class="siteActivity">
                                <a href="/activity-2/">Members feed</a>
                            </li>
                            <li class="membersNav">
                                <a href="/members">Members</a>
                            </li>
                            <li class="profileNav">
                                <a href="/members/<?= $user->user_nicename ?>/">Your profile</a>
                            </li>
                            <? else : ?>
                            <li><a href="/join-the-community/">Register</a></li>
                            <? endif; ?>
                            <li class="facesNav">
                                <a href="/members/faces-of-recovery">Faces of recovery</a>
                            </li>
                            <li class="communityGuideNav">
                                <a href="/members/community-guidelines">Community Guidelines</a>
                            </li>
                        </ul>
                    </aside>

                </div>
                <div class="col-sm-6 col-md-7 col-lg-8 facesPage">
                    
                    
                    <?php
                    // Start the Loop.
                    while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    //if ( comments_open() || get_comments_number() ) {
                    //comments_template();
                    //}
                    endwhile;
                    ?>

                    <p>The Living Sober ethos is one of sharing, tolerance, connection, understanding and kindness. We encourage warm, friendly and supportive dialogue. We welcome people thinking about stopping drinking or those that just wish to follow conversations (you lovely lurkers). We will not tolerate personal attacks, spamming, posts that break the law or trolling. </p>
                    
                    <p>
                        <b>Community Manager:</b>
                    </p>
                    
                    
                    <div class="cg-row row">
                        
                        <div class="col-xs-12">
                            
                            <a class="cg-avatar" href="<?=$lottaProfileLink?>">
                                <?=$lottaAvatar?>
                            </a>
                            <div class="cg-right">
                                <a class="cg-username" href="<?=$lottaProfileLink?>">
                                    @mrs-d
                                </a>
                                <?php if (false && $lottaRegisteredDate instanceof DateTime): ?>
                                <span class="cg-date">- has been a member since <?=$lottaRegisteredDate->format("F Y")?></span>
                                <?php endif; ?>
                                <p>(Lotta Dann) is the founder of Living Sober and our Community Manager. She produces all of the content on the site, supervises the Community Moderators and is on call 7 days a week to address any issues. Email her at any time on <a href="mailto:admin@livingsober.org.nz">admin@livingsober.org.nz</a>.</p>
                            </div>
                        </div>
                    </div>
                    
                    <p>
                        <b>Community Moderators:</b>
                    </p>
                    <p>
                    A handful of long-time members volunteer as Community Moderators. They visit the site regularly to interact with members, reinforce our ethos, answer queries and report any concerning behaviour.
                    </p>
                    
                    
                    <?php foreach ($quotes as $username => $attribs): ?>
                    
                    <?php 
                    # If the user isn't found in the DB, skip
                    if (!($attribs["user"] instanceof WP_User)) {
                        continue;
                    }
                    ?>
                    
                    
                    <?php 
                        $userObj = $attribs["user"];
                        
                        
                        $data = $userObj->data;
                        $profileLink = bp_core_get_user_domain($userObj->ID);
                        $registeredDate = DateTime::createFromFormat("Y-m-d H:i:s", $data->user_registered);
                        
                        $avatar = bp_core_fetch_avatar([
                            'item_id' => $userObj->ID, 
                            'type' => 'thumb'
                        ]);
              
                    ?>
                    <style>
                        .cg-row {
                            margin: 10px 0;
                        }
                        
                        .cg-username {
                            display: inline;
                            font-weight: bold;
                        }
                        .cg-date {
                            color: #999;
                            font-size: 80%;
                        }
                        .cg-avatar {
                            float: left;
                            margin-right: 10px;
                        }
                        
                        
                    </style>
                    <div class="cg-row row">
                        
                        <div class="col-xs-12">
                            
                            <a class="cg-avatar" href="<?=$profileLink?>">
                                <?=$avatar?>
                            </a>
                            <div class="cg-right">
                                <a class="cg-username" href="<?=$profileLink?>">
                                    @<?=$username?>
                                </a>
                                <?php if ($registeredDate instanceof DateTime): ?>
                                <span class="cg-date">- has been a member since <?=$registeredDate->format("F Y")?></span>
                                <?php endif; ?>
                                <p><?=$attribs["quote"]?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php endforeach; ?>

                </div>
                <aside class="col-xs-5 col-sm-3 col-lg-2 rightSide">

                    <?php include(dirname(__FILE__) . "/_days-sober.php"); ?>
                    <div class="toolBox">
                        <img src="/wp-content/themes/livingsober/images/sober-toolbox.png" alt="Sober toolbox" />
                        <p>Share tools and ideas that help you stay sober.</p>
                        <a class="btn btn-primary btn-orange" href="/sober-toolbox/">Sober toolbox</a>
                    </div>
                </aside>
            </div><!-- /row -->
        </div><!-- container -->
    </div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_footer();
