 function showtab(tabs)
                        {
                        var tab=tabs;
                        console.log(tab);
                        switch(tab) //this switch case replaces the tabContent
                       
                        {
                        case "tab-1":
                            document.getElementById('timeline').innerHTML = document.getElementById("tab-1").innerHTML;
                            break;
                        case "tab-2":
                            document.getElementById('timeline').innerHTML = document.getElementById("tab-2").innerHTML;
                            break;
                        case "tab-3":
                            document.getElementById('timeline').innerHTML = document.getElementById("tab-3").innerHTML;
                            break;
                        default:
                            document.getElementById('timeline').innerHTML = document.getElementById("tab-1").innerHTML;
                            break;
                        }
                    }