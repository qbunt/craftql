<?php

namespace markhuot\CraftQL\Types;

use yii\base\Component;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Craft;
use craft\elements\Entry;

class Query extends Component {

    private $sections;
    private $volumes;
    private $categoryGroups;
    private $assetVolumes;

    function __construct(
        \markhuot\CraftQL\Repositories\Volumes $volumes,
        \markhuot\CraftQL\Repositories\CategoryGroup $categoryGroups
    ) {
        $this->volumes = $volumes;
        $this->categoryGroups = $categoryGroups;
    }

    function getType($token) {
        $config = [
            'name' => 'Query',
            'fields' => [
                'helloWorld' => [
                    'type' => Type::string(),
                    'resolve' => function ($root, $args) {
                      return 'Welcome to GraphQL! You now have a fully functional GraphQL endpoint.';
                    }
                ],
            ],
        ];

        if ($token->can('query:entries')) {
            $config['fields']['entries'] = [
                'type' => Type::listOf(\markhuot\CraftQL\Types\Entry::interface()),
                'description' => 'Entries from the craft interface',
                'args' => \markhuot\CraftQL\Types\Entry::args(),
                'resolve' => function ($root, $args) {
                    $criteria = \craft\elements\Entry::find();
                    foreach ($args as $key => $value) {
                        $criteria = $criteria->{$key}($value);
                    }
                    return $criteria->all();
                }
            ];
        }

        if ($token->can('query:users')) {
            $config['fields']['users'] = [
                'type' => Type::listOf(\markhuot\CraftQL\Types\User::type()),
                'description' => 'Entries from the craft interface',
                'args' => \markhuot\CraftQL\Types\User::args(),
                'resolve' => function ($root, $args) {
                    $criteria = \craft\elements\User::find();
                    foreach ($args as $key => $value) {
                        $criteria = $criteria->{$key}($value);
                    }
                    return $criteria->all();
                }
            ];
        }

        return new ObjectType($config);
    }

    function getTypes($token) {
        $this->volumes->loadAllVolumes();
        $this->categoryGroups->loadAllGroups();

        return array_merge(
            $this->volumes->getAllVolumes(),
            $this->categoryGroups->getAllGroups(),
            \markhuot\CraftQL\Types\EntryType::some($token->queryableEntryTypeIds())
        );
    }

}