$(document).ready(function () {
    $(".byExcelShow").show();
    $(".byPhoneShow").hide();

    $(".byPhone").click(function (e) {
        $(".changeTitle").html("Bill Topup By Phone Number");
        $(".byPhoneShow").show();
        $(".byExcelShow").hide();
        $(".changeTitleData").html("Data Topup By Phone Number");
    });

    $(".byExcel").click(function (e) {
        $(".changeTitle").html("Bill Topup By Excel File");
        $(".byExcelShow").show();
        $(".byPhoneShow").hide();
        $(".changeTitleData").html("Data Topup By Excel File");
    });

    function checkSamePhone(file) {
        let files = file.files,
            f = files[0];

        let reader = new FileReader();
        reader.onloadend = function (e) {
            let data = new Uint8Array(e.target.result);
            let workbook = XLSX.read(data, { type: "array" });

            let count = workbook.SheetNames.map((sheetName) => {
                // object
                let XL_row_object = XLSX.utils.sheet_to_row_object_array(
                    workbook.Sheets[sheetName]
                );

                let getphNumbers = XL_row_object.map((val) => {
                    return val.phone_number;
                });

                let sortphNumbers = getphNumbers.slice().sort();

                let getSamePhNumbers = sortphNumbers.filter((v, i) => {
                    if (sortphNumbers[i] === sortphNumbers[i + 1]) {
                        return v;
                    }
                });

                let getSamePhNumbersLength = getSamePhNumbers.length;
                if (getSamePhNumbersLength > 0) {
                    return Swal.fire({
                        text: "System cannot be perform same Phone Number in same Amount at the same time!",
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonText: "Continue Anyway",
                    });
                }
            });
        };

        reader.readAsArrayBuffer(f);
    }
    // admin bill form
    $(".excelFile1").change(function () {
        let getfile = document.querySelector(".excelFile1");
        checkSamePhone(getfile);
    });

    // user bill form
    $(".excelFile2").change(function () {
        let getfile = document.querySelector(".excelFile2");
        checkSamePhone(getfile);
    });

    // admin data form
    $(".excelFile3").change(function () {
        let getfile = document.querySelector(".excelFile3");
        checkSamePhone(getfile);
    });

    // user data form
    $(".excelFile4").change(function () {
        let getfile = document.querySelector(".excelFile4");
        checkSamePhone(getfile);
    });
});
