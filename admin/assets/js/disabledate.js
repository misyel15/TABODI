
                    $(function() {
                    var currentYear = (new Date).getFullYear();
                    var currentMonth = (new Date).getMonth();
                    var currentDay = (new Date).getDate();

                    $("#from_date").datepicker({
                    minDate: new Date((currentYear - 1), 12, 1),
                    dateFormat: 'dd/mm/yy',
                    maxDate: new Date(currentYear, currentMonth, currentDay)
                    });
                    $("#to_date").datepicker({
                    minDate: new Date((currentYear - 1), 12, 1),
                    dateFormat: 'dd/mm/yy',
                    maxDate: new Date(currentYear, currentMonth, currentDay)
                    });
                    });
                