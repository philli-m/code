<?php
namespace rg\modules\examples\actions;

use rg\core\base\annotations\internal;
use rg\core\pow\PreparableAction;
use rg\core\pow\requirements\EntityRequirement;
use rg\core\pow\requirements\RequestDataRequirement;
use rg\core\pow\requirements\ServiceRequirement;
use rg\core\pow\requirements\WidgetRequirement;
use rg\model\accounts\entities\Account;
use rg\model\accounts\entities\Profile;
use rg\model\literature\entities\Publication;
use rg\model\literature\publication\PublicationService;
use rg\modules\publicliterature\actions\PublicationItem;

/**
 * @copyright ResearchGate GmbH
 */
class Intro extends PreparableAction {

    protected $allowedRenderingContexts = array(
        PreparableAction::RENDERING_CONTEXT_HTML, PreparableAction::RENDERING_CONTEXT_AJAX
    );

    // instructing IDE on what classes to import and data type, if class is undefined locate it using right click
    /**
     * @internal
     */

    /**
     * @var int
     */
    public $accountId;

    /**
     * @var Account
     */
    public $account;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var Publication[]
     */
    public $publications;

    /**
     * @var PublicationItem[]
     */
    public $publicationItems = [];

    //generator function, hence yield rather then return. it returns an object that can be iterated over
    public function collect() {
        yield [
            // new is the constructor for php
            //
            new RequestDataRequirement(
               $this->properties->accountId
            )];
        yield [
            new EntityRequirement(
                $this->properties->account,
                Account::class,
                ['id'=>$this->accountId]
            ),
            new EntityRequirement(
                $this->properties->profile,
                Profile::class,
                ['id'=>$this->accountId]
            ),
            ServiceRequirement(
                $this->properties->publications,
                PublicationService::getCall()->getPublicationsByAccountId($this->accountId)
            )
        ];

        //creating an empty array to be populated with key elements from
    $requirements = [];
        foreach($this->publications as $publication) {
            $requirements[] = new WidgetRequirement(
                $this->properties->publicationItems,
                PublicationItem::class,
            [
                    'publicationUid' => $publication->getPublicationUid(),
                    'publication' => $publication,
                ]
            );
        }

    yield $requirements;

    }




    /**
     * @return array
     * @internal
     */


    public function getData() {
        $data = [
            'fullName' => $this->account->getFullname(),
            'publicationItems' => $this->publicationItems,
        ];

        return $data;
    }
}
