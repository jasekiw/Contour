/**
 * Created by Jason on 4/7/2016.
 */
namespace newfacility {
    export class NewFacilityPage {
        constructor() {
            $(".step1").show();
        }
        public toProperties = (): void => {
            $(".step1").hide();
            $(".step2").show();
            window.scrollTo(0, 0);
        };
        public finish = () : void =>  {

        };
        public backToStart = () : void => {
            $(".step2").hide();
            $(".step1").show();
            window.scrollTo(0, 0);
        }


    }

}
