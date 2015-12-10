<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/2/2015
 * Time: 11:42 AM
 */

namespace app\libraries\excel\templates\imports\tags;

use \app\libraries\excel\Point;
use \app\libraries\types\Types;

/**
 * Class RuleConstruction
 * @package app\libraries\excel\templates\imports\tags
 */
class RuleConstruction
{

    /**
     * This function construction the import logic for the excel sheet. This is specifics of how the excel sheet will be imported
     * @return TemplateCollection
     */
    public function construct()
    {


        $template_sheets = new  TemplateCollection(); //creates the collection that all of the templates will be added to


        /**
         * EG Corporate Projections
         */
        $EGCorporateProjections = new ImportTemplate("EG Corporate Projections");
        $EGCorporateProjections->getRules()->add(ImportRule::headerTag( new Point(1, 4), new Point(1,64), Types::get_type_row() ) );
        $EGCorporateProjections->getRules()->add(ImportRule::headerTag(new Point(2, 2), new Point(14, 2), Types::get_type_column()));
        $template_sheets->add($EGCorporateProjections);

        /**
         * EG Corporate Roll Up
         */
        $EGPHCoporaterollUp = new ImportTemplate("EG - PH Corporate Roll UP" );
        $EGPHCoporaterollUp->getRules()->add(ImportRule::headerTag(new Point(1,1), new Point(1,71), Types::get_type_row() ));
        $EGPHCoporaterollUp->getRules()->add(ImportRule::headerTag(new Point(2,1), new Point(14,1), Types::get_type_column() ));
        $EGPHCoporaterollUp->getRules()->add(ImportRule::createExclusion(new Point(1,73), new Point(14,76) ));
        $template_sheets->add($EGPHCoporaterollUp);

        /**
         * PH Corporate Projections
         */
        $PHCorporateProjections = new ImportTemplate( "PH Coporate Projections" );
        $PHCorporateProjections->getRules()->add(ImportRule::headerTag(new Point(1, 3), new Point(1, 44), Types::get_type_row()));
        $PHCorporateProjections->getRules()->add(ImportRule::headerTag(new Point(2, 1), new Point(14, 1), Types::get_type_column()));
        $PHCorporateProjections->getRules()->add(ImportRule::createChildOf(new Point(1, 9), new Point(1, 42), new Point(1,8), Types::get_type_row()));
        $template_sheets->add($PHCorporateProjections);

        /**
         * Combined Revenue Summary
         */
        $CombinedRevenueSummary =new ImportTemplate( "Combined Revenue Summary");
        $CombinedRevenueSummary->getRules()->add(ImportRule::headerTag(new Point(1, 4), new Point(1, 99), Types::get_type_row()));
        $CombinedRevenueSummary->getRules()->add(ImportRule::headerTag(new Point(2, 3), new Point(14,3), Types::get_type_column()));
        $CombinedRevenueSummary->getRules()->add(ImportRule::createExclusion(new Point(1, 1), new Point(14,2)));
        $template_sheets->add($CombinedRevenueSummary);

        /**
         * Combined OP Profit Summary
         */
        $CombinedOPProfitSummary =new ImportTemplate("Combined OP Profit Summary");
        $CombinedOPProfitSummary->getRules()->add(ImportRule::headerTag(new Point(1, 4), new Point(1, 93), Types::get_type_row()));
        $CombinedOPProfitSummary->getRules()->add(ImportRule::headerTag(new Point(2, 3), new Point(14, 3), Types::get_type_column()));
        $CombinedOPProfitSummary->getRules()->add(ImportRule::createExclusion(new Point(1,96), new Point(14,102) ) );
        $CombinedOPProfitSummary->getRules()->add(ImportRule::createExclusion(new Point(1,1), new Point(14,2) ) );
        $template_sheets->add($CombinedOPProfitSummary);

        /**
         * Combined OP Profit Summary
         */
        $combinedOpSummary = new ImportTemplate("Combined OP Summary");
        $combinedOpSummary->getRules()->add(ImportRule::headerTag(new Point(2, 3), new Point(2, 36), Types::get_type_row()));
        $combinedOpSummary->getRules()->add(ImportRule::headerTag(new Point(3, 3), new Point(16, 4), Types::get_type_column()));
        $combinedOpSummary->getRules()->add(ImportRule::createChildOf(new Point(2,6),new Point(2,16), new Point(2,3), Types::get_type_row()));
        $combinedOpSummary->getRules()->add(ImportRule::createChildOf(new Point(2,20),new Point(2,26), new Point(2,18), Types::get_type_row() ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(2,38), new Point(14,49) ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(3,7), new Point(16,7) ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(3,9), new Point(16,9) ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(3,11), new Point(16,11) ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(3,13), new Point(16,13) ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(3,17), new Point(16,17) ) );
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(2,25),new Point(14,25) ));
        $combinedOpSummary->getRules()->add(ImportRule::createExclusion(new Point(1,1),new Point(14,2) ));
        $template_sheets->add($combinedOpSummary);



        /**
         * Phoenix OP Summary
         */
        $phoenixOPSummary = new ImportTemplate("Phoenix OP Summary");
        $phoenixOPSummary->getRules()->add(ImportRule::headerTag(new Point(1, 3), new Point(1, 30), Types::get_type_row()));
        $phoenixOPSummary->getRules()->add(ImportRule::headerTag(new Point(2, 4), new Point(13, 4), Types::get_type_column()));
        $phoenixOPSummary->getRules()->add(ImportRule::createChildOf(new Point(1,6), new Point(1,16), new Point(1,3), Types::get_type_row()));
        $phoenixOPSummary->getRules()->add(ImportRule::createChildOf(new Point(1,20),new Point(1,26), new Point(1,18), Types::get_type_row() ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,25),new Point(14,25) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(15,6),new Point(15,30) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,1),new Point(1,1) ));

        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,7),new Point(15,7) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,9),new Point(15,9) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1, 11),new Point(15,11) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,13),new Point(15,13) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,15),new Point(15,15) ));
        $phoenixOPSummary->getRules()->add(ImportRule::createExclusion(new Point(1,19),new Point(15,19) ));
        $template_sheets->add($phoenixOPSummary);


        /**
         * EG  OP Summary
         */
        $EgOpSummary = new ImportTemplate("EG  OP Summary");
        $EgOpSummary->getRules()->add(ImportRule::headerTag(new Point(2, 3), new Point(2, 30), Types::get_type_row()));
        $EgOpSummary->getRules()->add(ImportRule::headerTag(new Point(3, 4), new Point(14, 4), Types::get_type_column()));
        $EgOpSummary->getRules()->add(ImportRule::createChildOf( new Point(1,6), new Point(1,16), new Point(1,3), Types::get_type_row() ));
        $EgOpSummary->getRules()->add(ImportRule::createChildOf(new Point(1,20),new Point(1,26), new Point(1,18), Types::get_type_row() ));
        $EgOpSummary->getRules()->add(ImportRule::createExclusion(new Point(16, 6), new Point(16, 28)) );
        $EgOpSummary->getRules()->add(ImportRule::createExclusion(new Point(1, 1), new Point(1, 1)) );
        $EgOpSummary->getRules()->add(ImportRule::createExclusion(new Point(1,19),new Point(15,19) ));
        $EgOpSummary->getRules()->add(  ImportRule::createExclusion(new Point(2,32), new Point(28,32) ));
        $template_sheets->add($EgOpSummary);

        /**
         * EG  OP Summary
         */
        $budgetProjections = new ImportTemplate("Budget Projections" );
        $budgetProjections->getRules()->add(ImportRule::headerTag(new Point(1, 2), new Point(1, 878), Types::get_type_row()));
        $budgetProjections->getRules()->add(ImportRule::headerTag(new Point(3, 1), new Point(14, 1), Types::get_type_column()));
        $budgetProjections->getRules()->add(  ImportRule::createExclusion(new Point(2,1), new Point(2,1) ));


        for($i = 2; $i < 851; $i += 10)
        {
            $budgetProjections->getRules()->add( ImportRule::createChildOf( new Point(2,$i), new Point(2, $i + 9), new Point(1, $i), Types::get_type_row() ) );
        }

        $budgetProjections->getRules()->add( ImportRule::createChildOf( new Point(2,853), new Point(2, 862), new Point(1, 853), Types::get_type_row() ) );
        $budgetProjections->getRules()->add( ImportRule::createChildOf( new Point(2,867), new Point(2, 876), new Point(1, 867), Types::get_type_row() ) );
        $template_sheets->add($budgetProjections);



        /**
         * Facilities
         */
        $facilities = new ImportTemplate('facility');
        $facilities->getRules()->add(ImportRule::headerTag(new Point(2, 4), new Point(2, 60), Types::get_type_row()));
        $facilities->getRules()->add(ImportRule::headerTag(new Point(3, 3), new Point(14, 3), Types::get_type_column()));
        $facilities->getRules()->add(ImportRule::createChildOf( new Point(2,19),new Point(2,29), new Point(2,17), Types::get_type_row()  ));
        $facilities->getRules()->add( ImportRule::createChildOf( new Point(2,33),new Point(2,39), new Point(2,31), Types::get_type_row() ) );
        $facilities->getRules()->add( ImportRule::headerTag(new Point(16,17), new Point(16,17), Types::get_type_column() ));
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(15 ,4), new Point(16, 6)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,1), new Point(14, 2)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,20), new Point(28, 20)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,22), new Point(28, 22)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,24), new Point(28, 24)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,26), new Point(28, 26)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,28), new Point(28, 28)) );

        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,55), new Point(28, 55)) );
        $facilities->getRules()->add(ImportRule::createExclusion(new Point(1 ,57), new Point(28, 57)) );
        $template_sheets->add($facilities);


        /**
         * Special Facility - Maple Ridge
         */

        $mapleRidge = new ImportTemplate('Maple Ridge');
        $mapleRidge->getRules()->add(ImportRule::headerTag(new Point(3, 4), new Point(3, 60), Types::get_type_row()));
        $mapleRidge->getRules()->add(ImportRule::headerTag(new Point(4, 3), new Point(15, 3), Types::get_type_column()));
        $mapleRidge->getRules()->add(ImportRule::createChildOf( new Point(3,19),new Point(3,29), new Point(3,17), Types::get_type_row()  ));
        $mapleRidge->getRules()->add( ImportRule::createChildOf( new Point(3,33),new Point(3,39), new Point(3,31), Types::get_type_row() ) );
        $mapleRidge->getRules()->add( ImportRule::headerTag(new Point(17,17), new Point(17,17), Types::get_type_column() ));
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(17 ,4), new Point(17, 6)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,1), new Point(16, 2)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,20), new Point(29, 20)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,22), new Point(29, 22)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,24), new Point(29, 24)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,26), new Point(29, 26)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,28), new Point(29, 28)) );

        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,55), new Point(29, 55)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(2 ,57), new Point(29, 57)) );

        //random 0s to be taken out
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(4 ,30), new Point(29, 30)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(4 ,32), new Point(29, 32)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(4 ,38), new Point(29, 38)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(4 ,40), new Point(29, 40)) );
        $mapleRidge->getRules()->add(ImportRule::createExclusion(new Point(4 ,42), new Point(29, 42)) );
        $template_sheets->add($mapleRidge);



        /**
         * Special Facility - Saluda
         */

        $saluda = new ImportTemplate('Saluda');
        $saluda->getRules()->add(ImportRule::headerTag(new Point(2, 4), new Point(2, 60), Types::get_type_row()));
        $saluda->getRules()->add(ImportRule::headerTag(new Point(3, 3), new Point(14, 3), Types::get_type_column()));
        $saluda->getRules()->add(ImportRule::createChildOf( new Point(2,19),new Point(2,29), new Point(2,17), Types::get_type_row()  ));
        $saluda->getRules()->add( ImportRule::createChildOf( new Point(2,33),new Point(2,39), new Point(2,31), Types::get_type_row() ) );
        $saluda->getRules()->add( ImportRule::headerTag(new Point(16,17), new Point(16,17), Types::get_type_column() ));
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(16 ,4), new Point(16, 6)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,1), new Point(14, 2)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,20), new Point(28, 20)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,22), new Point(28, 22)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,24), new Point(28, 24)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,26), new Point(28, 26)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,28), new Point(28, 28)) );

        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,55), new Point(28, 55)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(1 ,57), new Point(28, 57)) );


        //random 0s to be taken out
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(3 ,30), new Point(29, 30)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(3 ,32), new Point(29, 32)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(3 ,38), new Point(29, 38)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(3 ,40), new Point(29, 40)) );
        $saluda->getRules()->add(ImportRule::createExclusion(new Point(3 ,42), new Point(29, 42)) );

        $saluda->getRules()->add(ImportRule::createExclusion(new Point(2 ,46), new Point(29, 49)) );
        $template_sheets->add($saluda);



        return $template_sheets;
    }
}