<?php

namespace App\Services;

class Plan {
        protected static $plans = [
                ['name' => 'gold', 'weight' => 2],
                ['name' => 'silver', 'weight' => 1] 
        ];

        /**
         * Get the plans
         *
         * @return Array
         */
        public static function getPlans(){
                return self::orderPlansByWeight();
        }


        /**
         * Order the plans by weight
         *
         * @return Array
         */
        private static function orderPlansByWeight(){
                usort(self::$plans, function ($item1, $item2) {
                        return $item2['weight'] <=> $item1['weight'];
                });

                return self::$plans;
        }
}

