<?php

/*
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2015-2020 (original work) Open Assessment Technologies SA ;
 *
 */

namespace oat\taoTestCenterRostering\helper;

use oat\taoClientDiagnostic\model\diagnostic\Paginator;

/**
 * Provides common data helper for datatable component.
 */
class DataTableHelper
{
    /**
     * The keyword for an ascending sort
     */
    private const SORT_ASC = 'asc';

    /**
     * The default sort order to use when none is provided
     */
    public const DEFAULT_SORT_ORDER = self::SORT_ASC;

    /**
     * @see Paginator::paginate()
     */
    public static function paginate($collection, array $options = array(), $dataRenderer = null): array
    {
        $result['success'] = true;
        $result = array_merge($result, (new Paginator())->paginate($collection, $options, $dataRenderer));

        return $result;
    }
}
