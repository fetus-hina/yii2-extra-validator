<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.0
 */

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\Validator;

/**
 * Validates twitter screeen_name (@id)
 */
class TwitterAccountValidator extends Validator
{
    /** @var string[] list of account-like string which user cannot use */
    public $nonUsernamePaths;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is not a valid twitter account name.');
        }
        if (!is_array($this->nonUsernamePaths)) {
            $this->nonUsernamePaths = [
                "about", "account", "accounts", "activity", "all", "announcements", "anywhere", "api_rules",
                "api_terms", "apirules", "apps", "auth", "badges", "blog", "business", "buttons", "contacts",
                "devices", "direct_messages", "download", "downloads", "edit_announcements", "faq", "favorites",
                "find_sources", "find_users", "followers", "following", "friend_request", "friendrequest", "friends",
                "goodies", "help", "home", "i", "im_account", "inbox", "invitations", "invite", "jobs", "list",
                "login", "logo", "logout", "me", "mentions", "messages", "mockview", "newtwitter", "notifications",
                "nudge", "oauth", "phoenix_search", "positions", "privacy", "public_timeline", "related_tweets",
                "replies", "retweeted_of_mine", "retweets", "retweets_by_others", "rules", "saved_searches", "search",
                "sent", "sessions", "settings", "share", "signup", "signin", "similar_to", "statistics", "terms",
                "tos", "translate", "trends", "tweetbutton", "twttr", "update_discoverability", "users", "welcome",
                "who_to_follow", "widgets", "zendesk_auth", "media_signup",
            ];
        }
    }

    /** @inheritdoc */
    public function validateAttribute($model, $attribute)
    {
        $ret = $this->validateValue($model->$attribute);
        if (is_array($ret)) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /** @inheritdoc */
    protected function validateValue($value)
    {
        {
        $value = strtolower((string)$value);
        if (!preg_match('/^[a-z0-9_]{1,15}$/', $value) || in_array($value, $this->nonUsernamePaths, true)) {
            return [$this->message, []];
            $this->addError($model, $attribute, $this->message);
        }
        return null;
        }
    }
}
