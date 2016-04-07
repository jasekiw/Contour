
class Main {
    public excelImportPage;
    public newFacilityPage : newfacility.NewFacilityPage;
    public executeExcelImportPage() : void
    {
        this.excelImportPage = new ExcelImportPage();
    }
    public executeNewFacilityPage()
    {
        this.newFacilityPage = new newfacility.NewFacilityPage();
    }
    
}