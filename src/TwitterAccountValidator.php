<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.0
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\Validator;

use function in_array;
use function is_array;
use function is_string;
use function preg_match;
use function strtolower;

/**
 * Validates twitter screeen_name (@id)
 */
class TwitterAccountValidator extends Validator
{
    /** @var string[]|null list of account-like string which user cannot use */
    public ?array $nonUsernamePaths = null;

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} is not a valid twitter account name.');
        }

        if (!is_array($this->nonUsernamePaths)) {
            $this->nonUsernamePaths = [
                'about', 'account', 'accounts', 'activity', 'all', 'announcements', 'anywhere', 'api_rules',
                'api_terms', 'apirules', 'apps', 'auth', 'badges', 'blog', 'business', 'buttons', 'contacts',
                'devices', 'direct_messages', 'download', 'downloads', 'edit_announcements', 'faq', 'favorites',
                'find_sources', 'find_users', 'followers', 'following', 'friend_request', 'friendrequest', 'friends',
                'goodies', 'help', 'home', 'i', 'im_account', 'inbox', 'invitations', 'invite', 'jobs', 'list',
                'login', 'logo', 'logout', 'me', 'mentions', 'messages', 'mockview', 'newtwitter', 'notifications',
                'nudge', 'oauth', 'phoenix_search', 'positions', 'privacy', 'public_timeline', 'related_tweets',
                'replies', 'retweeted_of_mine', 'retweets', 'retweets_by_others', 'rules', 'saved_searches', 'search',
                'sent', 'sessions', 'settings', 'share', 'signup', 'signin', 'similar_to', 'statistics', 'terms',
                'tos', 'translate', 'trends', 'tweetbutton', 'twttr', 'update_discoverability', 'users', 'welcome',
                'who_to_follow', 'widgets', 'zendesk_auth', 'media_signup',
            ];
        }
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function validateAttribute($model, $attribute)
    {
        $ret = $this->validateValue($model->$attribute);
        if (is_array($ret)) {
            $this->addError($model, $attribute, (string)$this->message);
        }
    }

    /**
     * @inheritdoc
     * @return array{string, array}|null
     */
    protected function validateValue($value)
    {
        if (!is_string($value)) {
            return [(string)$this->message, []];
        }

        $value = strtolower($value);
        if (
            !preg_match('/^[a-z0-9_]{1,15}$/', $value) ||
            in_array($value, (array)$this->nonUsernamePaths, true)
        ) {
            return [(string)$this->message, []];
        }

        return null;
    }
}
