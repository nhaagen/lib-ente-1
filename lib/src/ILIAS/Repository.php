<?php
/******************************************************************************
 * An entity component framework for PHP.
 *
 * Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>
 *
 * This software is licensed under GPLv3. You should have received a copy of
 * the license along with the code.
 */

namespace CaT\Ente\ILIAS;

/**
 * An repository over ILIAS objects.
 */
class Repository implements \CaT\Ente\Repository {
    /**
     * @var ProviderDB
     */
    private $provider_db;

    public function __construct(ProviderDB $provider_db) {
        $this->provider_db = $provider_db;
    }

    /**
     * @inheritdocs
     */
    public function providersForEntity(\CaT\Ente\Entity $entity, $component_type = null) {
        // This can only return entities for ILIAS
        if (!($entity instanceof Entity)) {
            return [];
        }
        return $this->provider_db->providersFor($entity->object(), $component_type);
    }

    /**
     * @inheritdocs
     */
    public function providersForComponentType($component_type, $entities = null) {
        $providers = $this->provider_db->providersOf($component_type);
		$returns = [];
		foreach ($providers as $provider) {
			$entity = $provider->entity();
			$id = $entity->id();
			if (!isset($returns[$id])) {
				$returns[$id] = ["entity" => $entity, "providers" => []];
			}
			$returns[$id]["providers"][] = $provider;
		}
		return array_values($returns);
    }
}
