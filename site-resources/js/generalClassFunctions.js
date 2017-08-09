//delcare class,
var Storage = {};

//This function writes to local storage
Storage.write = function(name, data) {
    if (data !== null && typeof data === 'object') {
        localStorage.setItem(name, JSON.stringify(data));
    } else {
        localStorage.setItem(name, data);
    }
}

//This function reads from local storage
Storage.read = function(name) {
    return localStorage.getItem(name);
}

Storage.forget = function(name) {
    localStorage.removeItem(name);
}

//declare class, User
var User = {};

//This function checks local storage for user login
User.checkLogin = function() {
    if (Storage.read("Userdata") == null) {
        return false;
    } else {
        var userData = JSON.parse(Storage.read("Userdata"));
        return userData;
    }
}

User.rememberUser = function(username, password) {
    var userData = {
        username: username,
        password: password
    };

    Storage.write("Userdata", userData);
}

//This function gets the username for the user for verification purposes
User.getUsername = function(username) {
    var postData = {
        username: username
    };
    var UserFlag = false;
    /*
    $.ajax({
        url: "site-resources/api/username.php",
        type: "POST",
        data: postData,
        async: false,
        success: function(data) {
            if (data == "success") {
                UserFlag = true;
            } else {
                UserFlag = false;
                console.log(data);
            }
        }
    });
    if (UserFlag == true) {
        return true;
    } else {
        return false;
    }

    return false;
    */
}

//determines whether password for user is valid
User.getPassword = function(username, password) {
    var postData = {
        username: username,
        password: password
    };
    var loginFlag = false;

    $.ajax({
        url: "site-resources/api/login.php",
        type: "POST",
        data: postData,
        async: false,
        success: function(data) {
            if (data == "success") {
                loginFlag = true;
            } else {
                loginFlag = false;
            }
        }
    });

    if (loginFlag == true) {
        return true;
    } else {
        return false;
    }

    return false;
}

//log the user out of the system
User.logout = function() {
    App.clearScreen($(".menu-container"));
    $(".opener").animate({
        top: "0"
    }).fadeIn();
    App.displayLogin();
    $("#info").html("myPALBOTICS");
    $("#password").attr({
        id: "username",
        placeholder: "Username",
        type: "text"
    }).val("");
    Storage.forget("Userdata");
}

//fetch User data
User.fetchData = function(username) {
    var postData = {
        username: username
    };

    $.ajax({
        url: "site-resources/api/fetch.php",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(data) {
            if (data.role != null) {
                App.showMenu(data.role, data.id);
                User["id"] = data.id;
                User["role"] = data.role;
                User["username"] = data.username;
                User["first_name"] = data.first;
                User["last_name"] = data.last;
                User["email"] = data.email;
            } else {
                console.log("error");
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Changes the User's password
User.changePassword = function(id, div) {
    var postData = {
        id: id
    };
    var flag = false;
    div.find("input, select").each(function() {
        var tag = $(this).attr("id");
        var value = $(this).val();

        if (value == "") {
            App.showMessage(div, $("<div class = 'error'>Make sure you fill all the fields out</div>"), 2000);
            flag = true;
        }

        postData[tag] = value;
    });

    if (postData["new"] != postData["new-confirm"]) {
        App.showMessage(div, $("<div class = 'error'>Your passwords do not match</div>"), 5000, true);
    } else if (flag == false) {
        $.ajax({
            url: "site-resources/api/change_password.php",
            type: "POST",
            data: postData,
            success: function(data) {
                if (data == "success") {
                    $(".modal").remove();
                    $(".filter-page").fadeOut();
                    App.showMessage($(".info-square"), $("<div class = 'good'>Password changed</div>"), 5000, true);
                } else {
                    App.showMessage(div, $("<div class = 'error'>Error: " + data + "</div>"), 5000, true);
                }
            },
            fail: function(data) {
                console.log(data);
            }
        });
    }
}

//Updates User's information
User.updateInfo = function(id, div) {
    var postData = {
        id: id
    };
    var flag = false;
    div.find("input, select").each(function() {
        var tag = $(this).attr("id");
        var value = $(this).val();

        if (value == "") {
            App.showMessage(div, $("<div class = 'error'>Make sure you fill all the fields out</div>"), 2000);
            flag = true;
        }

        postData[tag] = value;
    });

    if (flag == false) {
        $.ajax({
            url: "site-resources/api/update_user.php",
            type: "POST",
            data: postData,
            success: function(data) {
                if (data == "success") {
                    $(".modal").remove();
                    $(".filter-page").fadeOut();
                    $("#profile_info").empty();
                    User["username"] = postData.username;
                    User["first_name"] = postData.first;
                    User["last_name"] = postData.last;
                    User["email"] = postData.email;
                    App.editProfile(User.id);
                    App.showMessage($(".info-square"), $("<div class = 'good'>User Profile Updated</div>"), 5000, true);
                } else {
                    App.showMessage(div, $("<div class = 'error'>Error: " + data + "</div>"), 5000, true);
                }
            },
            fail: function(data) {
                console.log(data);
            }
        });
    }
}

//Reset a users' password by sending a code
User.passwordReset = function(username) {
    console.log(username)
    var postData = {
        username: username
    };

    $.ajax({
        url: "site-resources/api/reset_password.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                App.showMessage($(".title"), $("<div class = 'good' style = 'width:250px;'>An email with instructions to reset the password has been sent to the email on file.  Please allow up to 10 minutes to receive it.</div>"), 10000, false);
            } else {
                console.log(data);
            }
        }
    });
}

//Resets a password given a reset hash
User.getResetCode = function(reset_hash) {
    var postData = {
        hash: reset_hash
    };

    $.ajax({
        url: "site-resources/api/get_reset_hash.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                $("#info").html("Please enter a new password: ");
                $("#reset-code").attr({
                    id: "password",
                    placeholder: "Account Password",
                    type: "password"
                }).val("");

                App.reassignButton($("#reset-submit-btn"), function() {
                    User.resetPassword(reset_hash, $("#password").val());
                });
            } else {
                App.showMessage($(".title"), $("<div class = 'error'>That reset code does not exist</div>"), 2000);
            }
        }
    });
}

//resets user password (for real)
User.resetPassword = function(reset_hash, newpassword) {
    var postData = {
        hash: reset_hash,
        pass: newpassword
    };

    $.ajax({
        url: "site-resources/api/reset_password_change.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                App.showMessage($(".title"), $("<div class = 'good' style = 'width:250px'>Password reset successful, you will be redirected to login in 3 seconds.</div>"), 3000, false);
                setTimeout(function() {
                    window.location.replace("http://my.palbotics.org");
                }, 3000);
            } else {
                App.showMessage($(".title"), $("<div class = 'error'>Error: " + data + "</div>"), 3000, false);
            }
        },
        fail: function(data) {
            alert("failure");
            console.log(data);
        }
    });
}


//declares class, App
var App = {};

App.displayLogin = function() {
    $("#login-submit-btn").click(function() {
        if (User.getUsername($("#username").val()) == false) {
            App.showMessage($(".title"), $("<div class = 'error'>Username does not exist</div>"), 2000);
        } else if (User.getUsername($("#username").val()) == true) {
            User["username"] = $("#username").val();
            $("#info").html("Welcome <b>" + $("#username").val() + "</b>, <br>please input password");
            $("#username").attr({
                id: "password",
                placeholder: "Account Password",
                type: "password"
            }).val("");
            $(".title").append($("<div id = 'password-reset'>Forgot your password?</div><div id = 'forget'>Keep me Logged In <input type = 'checkbox' id = 'remember' /><label for = 'remember'><span></span></label></div>"));
            $("#password-reset").click(function() {
                User.passwordReset(User["username"]);
            });
            //$("#login-submit-btn").css("opacity","0");


            App.reassignButton($("#login-submit-btn"), function() {
                if (User.getPassword(User.username, $("#password").val()) == true) {
                    User.rememberUser(User.username, $("#password").val());
                    $(".opener").animate({
                        top: "-100%"
                    }).fadeOut();
                    User.fetchData(User.username);
                    $("#password-reset").remove();
                    $("#forget").remove();
                } else {
                    App.showMessage($(".title"), $("<div class = 'error'>Wrong Password</div>"), 2000);
                }
            });
        }
    });
}

//declare Jquery element variables for use in page loading

//This function reassigns function of a button
App.reassignButton = function(button, functionality) {
    button.off("click").click(functionality);
}

//This function will show a given message (message) in the target element (target)
App.showMessage = function(target, message, timed, prepend) {
    if (prepend == true) {
        message.prependTo(target);
    } else {
        message.appendTo(target);
    }
    if (timed != null) {
        setTimeout(function() {
            message.fadeOut();
        }, timed);
    }
}

//This function clears the screen of the current page
App.clearScreen = function(target) {
    target.animate({
        opacity: 0,
        left: "100%"
    }, function() {
        $(this).remove();
    });
}

//creates actionable screen
App.createScreen = function(title, id, content, before) {
    var screen = $("<div class = 'fullscreen'><div class = 'header'>" + title + "</div><div class = 'content' id = " + id + "></div></div>");
    screen.appendTo($("body")).css("opacity", "0").delay(500).animate({
        opacity: "1"
    });

    if (before == null) {
        content.appendTo($(".content"));
    } else {
        content.appendTo($(".fullscreen"));
    }
}

//This function generates the menu button
App.backMenu = function(role, id) {
    var back = $("#back");
    back.fadeIn();
    back.click(function() {
        App.showMenu(role, id);
        App.clearScreen($(".fullscreen"));
        $(this).fadeOut();
    });
}

//This function  displays and allows user to edit account info
App.editProfile = function(id) {
    var div = $("<div class = 'info-square'><b>Username: </b>" + User.username + "<br><br><b>First Name:</b> " + User.first_name + "<br><br><b>Last Name:</b> " + User.last_name + "<br><br><b>Email:</b> " + User.email + "<br><br><b>Account Type:</b> " + User.role + "<br><button type = 'submit' class = 'back' id = 'edit-profile' data-app = " + User.id + " style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Edit Account Information</button><button type = 'submit' class = 'back' id = 'pass' data-app = " + User.id + " style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Change Account Password</button></div>");

    div.appendTo($("#profile_info"));

    $("#pass").click(function() {
        App.createModal("Change your Password", "<table style  = 'border:0;'><tr><td><b>Current Password:</b></td><td><input type = 'password' class  = 'small-input' id  = 'old' required></td></tr><tr style = 'background-color:white;'><td><b>New Password:</b></td><td><input type = 'password' class  = 'small-input' id  = 'new' required></td></tr><tr><td><b>Confirm New Password:</b></td><td><input type = 'password' class  = 'small-input' id  = 'new-confirm' required></td></table><button type = 'submit' class = 'back' id = 'edit' style = 'margin-top:20px;margin-bottom:0;position:relative;left:50%;transform:translatex(-50%);'>Save Changes</button>");
        App.createModal("Change your Password", "<table style  = 'border:0;'><tr><td><b>Current Password:</b></td><td><input type = 'password' class  = 'small-input' id  = 'old' required></td></tr><tr style = 'background-color:white;'><td><b>New Password:</b></td><td><input type = 'password' class  = 'small-input' id  = 'new' required></td></tr><tr><td><b>Confirm New Password:</b></td><td><input type = 'password' class  = 'small-input' id  = 'new-confirm' required></td></table><button type = 'submit' class = 'back' id = 'edit' style = 'margin-top:20px;margin-bottom:0;position:relative;left:50%;transform:translatex(-50%);'>Save Changes</button>");
        $("#edit").off("click").click(function() {
            User.changePassword(User.id, $(".modal-content"));
        });
    });

    $("#edit-profile").click(function() {
        App.createModal("Edit your Account Information", "<table style  = 'border:0;'><tr><td><b>First Name:</b></td><td><input type = 'text' class  = 'small-input' id  = 'first' value = '" + User.first_name + "' required></td></tr><tr style = 'background-color:white;'><td><b>Last Name:</b></td><td><input type = 'text' class  = 'small-input' id  = 'last' value = '" + User.last_name + "' required></td></tr><tr><td><b>Email Address:</b></td><td><input type = 'text' class  = 'small-input' id  = 'email' value = '" + User.email + "' required></td></tr><tr><td><b>Username:</b></td><td><input type = 'text' class  = 'small-input' id  = 'username' value = '" + User.username + "' required></td></tr></table><button type = 'submit' class = 'back' id = 'save' style = 'margin-top:20px;margin-bottom:0;position:relative;left:50%;transform:translatex(-50%);'>Save Changes</button>");
        $("#save").off("click").click(function() {
            User.updateInfo(User.id, $(".modal-content"));
        });
    });

}

//This function generates and displays the menu for the db
App.showMenu = function(role, uid) {

    var option = "";

    function leadMentor() {
        option = "<li id = 'profile'>Account Management</li><li id = 'pending'>Pending Registrations</li><li id = 'accepted'>Accepted Registrations</li><li id = 'programmed'>Programs</li><li id = 'mentors'>Mentors</li><li id = 'current-standings'>Current Program Standings</li><li id = 'certificate' >Generate Certificates</li><li id = 'messages'>Messages</li><li id = 'logout'>Logout</li>";

        var menu = $("<div class = 'menu-container'><ul class = 'menu'>" + option + "</ul></div>");
        menu.appendTo("body").delay(750).animate({
            opacity: "1",
            //left: "0%"
        }, 500);

        Data.checkPending();
        Data.checkMessages(uid);

        $("#certificate").off("click").click(function() {
            window.open("certificates.php");
        });
        $("#current-standings").off("click").click(function() {
            window.open("./standings.php");
        });
        $("#logout").off("click").click(function() {
            User.logout();
        });
        $("#profile").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Account Management", "profile_info", $("<div>"));
            App.editProfile(uid);
        });
        $("#mentors").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Mentor Assignment", "app", $("<table id = 'mentors_apps'></table>"));
            Data.listMentors();
        });
        $("#pending").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Pending Applications", "app", $("<table id = 'applications'></table>"));
            Data.listPending();
        });
        $("#programmed").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Program Scheduler", "programs", $("<table id = 'program-data'></table><button type = 'submit' class = 'back' id = 'new-program' style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Start New Program</button>"));
            Data.listPrograms($("#program-data"));
            $("#new-program").off("click").click(function() {
                Data.newProgram();
            });
        });
        $("#accepted").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Accepted Applications", "coolbeans", $("<table id = 'acceptance'></table>"));
            Data.listAccepted();
        });
        $("#programs").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            //App.createScreen("Edit Entries","db", edit);
        });
        $("#messages").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Messages", "main_ting", $("<table id = 'message-div' />"));
            Data.listMessages(uid);
        });
    }

    function parent() {
        option = "<li id = 'profile'>Account Management</li><li id = 'pending'>Pending Enrollments</li><li id = 'current-enrolement'>Current Enrollments</li><li id = 'review'>Review Programs</li><li id = 'messages'>Messages</li><li id = 'logout'>Logout</li>";

        Data.checkPending(uid);
        Data.checkMessages(uid);

        var menu = $("<div class = 'menu-container'><ul class = 'menu'>" + option + "</ul></div>");
        menu.appendTo("body").delay(750).animate({
            opacity: "1",
            left: "0%"
        }, 500);

        $("#logout").off("click").click(function() {
            User.logout();
        });
        $("#review").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Review Programs", "review", $("<div>"));
            Data.reviewPrograms(User.id, Data.parentialReview);
        });
        $("#profile").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Account Management", "profile_info", $("<div>"));
            App.editProfile(uid);
        });
        $("#pending").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Pending Enrollments", "app", $("<table id = 'applications'></table>"));
            Data.listPending(uid);
        });
        $("#current-enrolement").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Accepted Enrollments", "coolbeans", $("<table id = 'acceptance'></table><br><div><b>(Click on row to view full application)</b></div>"));
            Data.listAccepted(uid);
        });
        $("#messages").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Messages", "main_ting", $("<table id = 'message-div' />"));
            Data.listMessages(uid);
        });
    }

    function mentor() {
        option = "<li id = 'profile'>Account Management</li><li id = 'assignment'>View Assignment</li><li id = 'manage'>Manage Teams</li><li id = 'products'>Go to Product Vendors</li><li id = 'messages'>Messages</li><li id = 'logout'>Logout</li>";

        var menu = $("<div class = 'menu-container'><ul class = 'menu'>" + option + "</ul></div>");
        menu.appendTo("body").delay(750).animate({
            opacity: "1",
            left: "0%"
        }, 500);

        Data.checkPending();
        Data.checkMessages(uid);

        $("#logout").off("click").click(function() {
            User.logout();
        });
        $("#products").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Product Vendor Directory", "links", $("<div><a href = 'vendors/control'>National Control Systems</a><br><a href = 'vendors/motion'>HandyMark</a><br><a href = 'vendors/structure'>Macalyte-Trucc</a></div>"))
        });
        $("#profile").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Account Management", "profile_info", $("<div />"));
            App.editProfile(uid);
        });
        $("#assignment").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Program Assignment", "program_assignment", $("<div>"));
            Data.viewAssignment(uid);
        });
        $("#manage").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Manage Teams", "manage_teams", $("<div>"));
            Data.checkActiveAssignments(uid, "in-progress", Data.manageTeam);
            //Data.checkActiveAssignments(uid, "in-progress", Data.manageTeam);
        });
        $("#messages").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Messages", "main_ting", $("<table id = 'message-div' />"));
            Data.listMessages(uid);
        });
    }

    function participant() {
        option = "<li id = 'profile'>Account Management</li><li id = 'awards'>Award Submissions</li><li id = 'parts'>Budgeting</li><li id = 'messages'>Messages</li><li id = 'logout'>Logout</li>";

        var menu = $("<div class = 'menu-container'><ul class = 'menu'>" + option + "</ul></div>");
        menu.appendTo("body").delay(750).animate({
            opacity: "1",
            left: "0%"
        }, 500);

        Data.checkPending();
        Data.checkMessages(uid);

        $("#logout").off("click").click(function() {
            User.logout();
        });
        $("#profile").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Account Management", "profile_info", $("<div>"));
            App.editProfile(uid);
        });
        $("#parts").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Program Assignment", "program_assignment", $("<div>"));
            Data.viewAssignment(uid);
        });
        $("#awards").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Manage Teams", "manage_teams", $("<div>"));
            Data.checkActiveAssignments(uid, "in-progress", Data.manageTeam);
        });
        $("#messages").off("click").click(function() {
            App.clearScreen($(".menu-container"));
            App.backMenu(role, uid);
            App.createScreen("Messages", "main_ting", $("<table id = 'message-div' />"));
            Data.listMessages(uid);
        });
    }

    switch (role) {
        case "Lead Mentor":
            leadMentor();
            break;
        case "parent":
            parent();
            break;
        case "mentor":
            mentor();
            break;
        case "participant":
            participant();
            break;
        default:
            break;
    }
}

App.createInfoModal = function(first) {
    $(".modal").remove();
    $(".filter").remove();
    var appDiv = "<b>Participant Name:</b> " + first['first'] + " " + first['last'] + "<br><b>Parent Name:</b> " + first["first_parent"] + " " + first["last_parent"] + "<br><b>Address: </b>" + first["street_1"] + " " + first["street_2"] + " " + first["city"] + ", " + first["state"] + " " + first["zip"] + "<br><b>Email: </b>" + first["email"] + "<br><b>Phone Number: </b>" + first["phone"] + "<br><b>Gender: </b>" + first["gender"] + "<br><b>School: </b>" + first["school"] + " in the " + first["grade"] + " Grade<br><b>Shirt Size: </b>" + first["shirt"] + "<br><b>Emergency Contact Information: </b>" + first["emergency_name"] + " &nbsp&nbsp&nbsp" + first["emergency_phone"] + "<br><b>Program Enrollment Information: </b>" + first["age"] + " " + first["program"];
    App.createModal("Application Information", appDiv);
}

App.slideShow = function(obj) {
    var className = obj.attr("class");
    alert("4");
    obj.css({
        overflow: "hidden",
        height: "360px",
        width: "100%"
    });
    $("." + className + ">ul").css({
        listStyleType: "none",
        position: "relative",
        padding: 0
    });
    /*$("."+className+">li").css({
      width:"100%",
      position:"absolute",
      left:"-100%"
    });*/
    if ($(".nu").length) {
        alert("1j");
    }
    $(".nu").css({
        width: "100%"
    });
}


//This function hides the current db entry and returns to hidden navigation
App.backDB = function(obj) {
    $("<button type = 'submit' class = 'back' style = 'display:block;top:100px;transform:translateY(-50%);left:30px;margin:0;'>Back</button>").appendTo(".fullscreen").click(function() {
        //$(".thumb").show();
        obj
            .css({
                width: "600px",
                height: "175px",
                padding: "10px",
                display: "block",
                border: "0px solid black",
                overflow: "hidden"
            })
            .find(".item-title")
            .click({
                param1: obj
            }, App.thumbClick);

        obj.find(".thumb_im").css({
            height: "100%",
            width: "40%"
        }).find(".square").css({
            width: "100%",
            height: "auto"
        });
        $(".thumb").not(obj).css("opacity", "0").show();
        setTimeout(function() {
            $(".thumb").animate({
                opacity: "1"
            });
        }, 500);
        //$(".thumb").show();
        $(".small-reel").hide();
        $(this).remove();
    });
}

//Creates a pop-up modal to alert the user of information of user input
App.createModal = function(header, content) {
    var container = $("<div class = 'modal'><div class = 'modal-header'>" + header + "<span class = 'close'>&#10006;</span></div><div class = 'modal-content'>" + content + "</div></div>");
    container.appendTo(".modals-container").css({
        display: "none"
    }).fadeIn();
    $(".filter-page").fadeIn();
    $(".close").off('click').click(function() {
        $(".modal").remove();
        $(".filter-page").fadeOut();
    });
    $(".modal-header").css("cursor", "move");
    $(".modal").draggable({
        handle: ".modal-header",
        containment: "body",
        start: function() {
            $(this).css("transform", "translate(0)");
        }
    });
}

//Creates a modal specifically for user input in writing messages.
App.createMessage = function(dest, src, subject, body) {
    App.createModal("<input type = 'text' value = '" + subject + "' class = 'messages-input' id = 'msubj'>", "<textarea id = 'body'></textarea><button class = 'send' type = 'submit' style = 'opacity:1;'>Send</button>");

    $(".send").off("click").click(function() {
        Data.writeMessage(dest, src, $("#msubj").val(), $("#body").val());
    });
}

//Cycles through all form elements in a given div and returns object with data extracted from said form
App.cycle = function(container) {
    var points = {};
    container.find("input, select").each(function() {
        var tag = $(this).attr("id");
        var value = $(this).val();

        points[tag] = value;
    });
    return points;
}

//shows loading icon when ajax query is processing
App.loading = function(target) {
    target.append($("<image src='site-resources/images/logo.png' class = 'loading'>"));
}


//Declare the database variable
var Data = {};

//Checks if any pending applications exist
Data.checkPending = function(uid) {
    if (uid == null) {
        var postData = {
            uid: -1
        };

        $.ajax({
            url: "site-resources/api/check_pending.php",
            type: "POST",
            data: postData,
            success: function(data) {
                if (data == "success") {
                    $("#pending").addClass("attention");
                } else {
                    if ($("#pending").hasClass("attention") == true) {
                        $("#pending").removeClass("attention");
                    }
                }
            },
            error: function(data) {
                console.log("error");
            }
        });
    } else {
        var postData = {
            uid: uid
        };
        $.ajax({
            url: "site-resources/api/check_pending.php",
            type: "POST",
            data: postData,
            success: function(data) {
                if (data == "success") {
                    $("#pending").addClass("attention");
                } else {
                    if ($("#pending").hasClass("attention") == true) {
                        $("#pending").removeClass("attention");
                    }
                }
            },
            error: function(data) {
                console.log("error");
            }
        });
    }
}

//Lists pending applications for programs.
Data.listPending = function(uid) {
    if (uid == null) {
        uid = -1;
        var postData = {
            uid: uid
        };
        App.loading($(".content"));
        $.ajax({
            url: "site-resources/api/list_pending.php",
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(data) {
                $(".loading").remove();
                var header = $("<tr id = 'fixed'><th>App ID</th><th>First Name</th><th>Last Name</th><th>Age</th><th>Grade</th><th>Program</th><th>Program Session</th><th>Timestamp</th><th>Email</th><th>Phone</th><th>Action</th></tr>");
                $("#applications").append(header);
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        var cells = "<td>" + data[i]['id'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td>" + data[i]['age'] + "</td><td>" + data[i]['grade'] + "</td><td>" + data[i]['program'] + "</td><td>" + data[i]['program_time'] + "</td><td>" + data[i]['timestamp'] + "</td><td>" + data[i]['email'] + "</td><td>" + data[i]['phone'] + "</td><td><select class = 'action' id = '" + data[i]['id'] + "'><option>Action</option><option>Delete</option><option>Accept</option></select></td>";

                        var div = $("<tr>" + cells + "</tr>");
                        $("#applications").append(div);
                    }
                    $(".action").on("change", function() {
                        var c = confirm("Confirm Action: " + $(this).val());

                        if (c == true) {
                            if ($(this).val() == "Delete") {
                                Data.deleteEntry($(this).attr("id"));
                            } else if ($(this).val() == "Accept") {
                                Data.acceptEntry($(this).attr("id"));
                            } else {
                                alert("Unknown Request");
                            }
                        }
                    });
                } else {
                    $(".content").empty().append($("<div>NO NEW REGISTRATIONS</div>"))
                    console.log(data);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        var postData = {
            uid: uid
        };

        $.ajax({
            url: "site-resources/api/list_pending.php",
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(data) {

                var header = $("<tr id = 'fixed'><th>App ID</th><th>First Name</th><th>Last Name</th><th>Age</th><th>Program</th><th>Program Session</th><th>Timestamp</th><th>Action</th></tr>");
                $("#applications").append(header);
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        var cells = "<td>" + data[i]['id'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td>" + data[i]['age'] + "</td><td>" + data[i]['program'] + "</td><td>" + data[i]['program_time'] + "</td><td>" + data[i]['timestamp'] + "</td><td><select class = 'action' id = '" + data[i]['id'] + "'><option>Action</option><option>Drop</option></select></td>";

                        var div = $("<tr>" + cells + "</tr>");
                        $("#applications").append(div);
                    }
                    $(".action").on("change", function() {
                        var c = confirm("Confirm Action: " + $(this).val());

                        if (c == true) {
                            if ($(this).val() == "Delete") {
                                Data.deleteEntry($(this).attr("id"));
                            } else if ($(this).val() == "Accept") {
                                Data.acceptEntry($(this).attr("id"));
                            } else {
                                alert("Unknown Request");
                            }
                        }
                    });
                } else {
                    console.log(data);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
}

//Lists all accepted applications and their status
Data.listAccepted = function(uid) {
    if (uid == null) {
        uid = -1;
        var postData = {
            uid: uid
        };
        App.loading($(".content"));
        $.ajax({
            url: "site-resources/api/list_accepted.php",
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(data) {
                $(".loading").remove();
                var header = $("<tr id = 'fixed'><th>App ID</th><th>First Name</th><th>Last Name</th><th>Age</th><th>Program</th><th>Program Session</th><th>Status</th><th>Action</th></tr>");
                $("#acceptance").append(header);
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        var cells = "<td>" + data[i]['id'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td>" + data[i]['age'] + "</td><td>" + data[i]['program'] + "</td><td>" + data[i]['program_time'] + "</td><td>" + data[i]['status'] + "</td><td><select class = 'action' id = '" + data[i]['id'] + "' data-uid = '" + data[i]["uid"] + "'><option>Action</option><option>Reject</option><option>Message</option></select></td>";

                        //var first = data[i];

                        var div = $("<tr>" + cells + "</tr>");
                        div.appendTo($("#acceptance")).css("cursor", "pointer").on("click", {
                                arg1: data[i]
                            }, function(e) {
                                var second = e.data.arg1;

                                App.createInfoModal(second);
                            })
                            .find("select").click(function() {
                                return false;
                            });

                    }
                    $(".action").on("change", function() {
                        var c = confirm("Confirm Action: " + $(this).val());

                        if (c == true) {
                            if ($(this).val() == "Reject") {
                                Data.rejectEntry($(this).attr("id"));
                            } else if ($(this).val() == "Message") {
                                App.createMessage($(this).attr("data-uid"), 1, "Subject");
                            } else {
                                alert("Unknown Request");
                            }
                        }
                    });
                } else {
                    console.log(data);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        var postData = {
            uid: uid
        };
        App.loading($(".content"));
        $.ajax({
            url: "site-resources/api/list_accepted.php",
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(data) {
                $(".loading").remove();
                var header = $("<tr id = 'fixed'><th>App ID</th><th>First Name</th><th>Last Name</th><th>Age</th><th>Program</th><th>Program Session</th><th>Status</th><th>Action</th></tr>");
                $("#acceptance").append(header);
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        switch (data[i]["status"]) {
                            case "Unverified (Accepted)":
                                var options = "<option>Confirm Acceptance</option>";
                                pl = "attention-message";
                                break;
                            case "Material Fee Unpaid":
                                var options = "<option>Pay Fee</option>";
                                pl = "attention-message";
                                break;
                            default:
                                var option = "";
                                pl = "";
                                break;
                        }

                        var cells = "<td>" + data[i]['id'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td>" + data[i]['age'] + "</td><td>" + data[i]['program'] + "</td><td>" + data[i]['program_time'] + "</td><td>" + data[i]['status'] + "</td><td><select class = 'action' id = '" + data[i]['id'] + "'><option>Action</option><option>Drop out</option>" + options + "</select></td>";

                        var div = $("<tr class = '" + pl + "'>" + cells + "</tr>");
                        div.appendTo($("#acceptance")).css("cursor", "pointer").on("click", {
                                arg1: data[i]
                            }, function(e) {
                                var first = e.data.arg1;

                                var appDiv = "<b>Participant Name:</b> " + first['first'] + " " + first['last'] + "<br><b>Parent Name:</b> " + first["first_parent"] + " " + first["last_parent"] + "<br><b>Address: </b>" + first["street_1"] + " " + first["street_2"] + " " + first["city"] + ", " + first["state"] + " " + first["zip"] + "<br><b>Email: </b>" + first["email"] + "<br><b>Phone Number: </b>" + first["phone"] + "<br><b>Gender: </b>" + first["gender"] + "<br><b>School: </b>" + first["school"] + " in the " + first["grade"] + " Grade<br><b>Shirt Size: </b>" + first["shirt"] + "<br><b>Emergency Contact Information: </b>" + first["emergency_name"] + " &nbsp&nbsp&nbsp" + first["emergency_phone"] + "<br><b>Program Enrollment Information: </b>" + first["age"] + " | " + first["program"] + " | " + first["program_time"] + "<br><button type = 'submit' class = 'back' id = 'edit' data-app = " + first["id"] + " style = 'margin-top:20px;margin-bottom:0;position:relative;left:50%;transform:translatex(-50%);'>Edit Application</button>";
                                App.createModal("Application Information", appDiv);

                                $("#edit").off("click").on("click", function() {
                                    $(".modal-content").empty().css("overflow-y", "scroll");
                                    var form = $("<b>Participant Name:</b><br> <input type = 'text' value = '" + first['first'] + "' id = 'first' class = 'small-input' placeholder = 'First Name' required> <input type = 'text' value = '" + first['last'] + "' id  = 'last' class = 'small-input' placeholder = 'Last Name' required><br><br><b>Parent Name:</b><br> <input type = 'text' value = '" + first['first_parent'] + "' id = 'first_parent' class = 'small-input' placeholder = 'First Name' required>  <input type = 'text' value = '" + first['last_parent'] + "' id = 'last_parent' class = 'small-input' placeholder = 'Last Name' required> <br><br><b>Address: </b><br><input type = 'text' value = '" + first['street_1'] + "' id  = 'street_1' class = 'small-input' placeholder = 'Address Line 2' required> <input type = 'text' value = '" + first['street_2'] + "' id = 'street_2' class = 'small-input' placeholder = 'Address Line 2'> <input type = 'text' value = '" + first['city'] + "' id = 'city' class = 'small-input' placeholder = 'City' required>, <input type = 'text' value = '" + first['state'] + "' class = 'small-input' id = 'state' placeholder = 'State' required> <input type = 'text' value = '" + first['zip'] + "' id = 'zip' class = 'small-input' placeholder = 'Zipcode' required><br><br><b>Email: </b><br><input type = 'text' value = '" + first['email'] + "' id  = 'email' class = 'small-input' placeholder = 'Email Address' required><br><br><b>Phone Number: </b><br><input type = 'text' value = '" + first['phone'] + "' id = 'phone' class = 'small-input' placeholder = 'Phone Number' required><br><br><b>Gender: </b><br><select id = 'gender'><option>" + first["gender"] + "</option><option>Male</option><option>Female</option></select><br><br><b>School: </b><br><input type = 'text' value = '" + first['school'] + "' id = 'school' class = 'small-input' required> in the <br><input type = 'text' value = '" + first['grade'] + "' id = 'grade' class = 'small-input' required> Grade<br><br><b>Shirt Size: </b>" + first["shirt"] + "<br><br><b>Emergency Contact Information: </b><br><input type = 'text' value = '" + first['emergency_name'] + "' id = 'emergency_name' class = 'small-input' placeholder = 'Contact Name' required><br><input type = 'text' value = '" + first['emergency_phone'] + "' id = 'emergency_phone' class = 'small-input' placeholder = 'Contact Phone Number' required><br><br><b>Program Information</b><br><i>Program Enrollment Age Group: </i><select id  = 'age-group'><option>" + first["age"] + "</option><option>PALBOTICS Boost: 8 - 11 years old</option><option>PALBOTICS Prime: 12 - 14 years old</option></select><br><i>Program Type:</i><select id = 'program'><option>" + first["program"] + "</option><option>PALBOTICS League</option><option>PALBOTICS Academy</option><option>PALBOTICS Flint</option><option>PALBOTICS Eclipse</option></select><br><i>Program Session:</i> <select id = 'session'><option>" + first["program_time"] + "</option><option>Early Spring, April 2 - May 7</option><option>Late Spring, May 14 - June 13</option><option>Summer 1, July 25 - July 30</option><option>Summer 2, August 1 - August 6</option></select><br>");

                                    var save = $("<button type = 'submit' class = 'back' id = 'save' data-app = " + first["id"] + " style = 'margin-top:20px;margin-bottom:0;position:relative;left:50%;transform:translatex(-50%);'>Save Changes</button>");

                                    form.appendTo($(".modal-content"));
                                    save.appendTo($(".modal-content")).off("click").on("click", function() {
                                        Data.updateEntry($(".modal-content"), first["id"], uid);
                                    });
                                });
                            })
                            .find("select").click(function() {
                                return false;
                            });
                    }
                    $(".action").on("change", function() {
                        var c = confirm("Confirm Action: " + $(this).val());

                        if (c == true) {
                            if ($(this).val() == "Drop out") {
                                Data.dropEntry($(this).attr("id"), uid);
                            } else if ($(this).val() == "Confirm Acceptance") {
                                var d = confirm("I confirm that my child will be able to attend the specified event at the specified time.");
                                if (d == true) {
                                    Data.verifyEntry($(this).attr("id"), uid);
                                } else {
                                    alert("If you are not able to confirm attendence at this time, please message program director with an update of your status.");
                                }
                            } else if ($(this).val() == "Pay Fee") {
                                alert("Payment Unavailable at this time.");
                            } else {
                                alert("Unknown Request");
                            }
                        }
                    });
                } else {
                    console.log(data);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
}

//update entrey with given information
Data.updateEntry = function(div, id, uid) {
    var postData = {
        id: id
    };
    div.find("input, select").each(function() {
        var tag = $(this).attr("id");
        var value = $(this).val();

        postData[tag] = value;
    });

    $.ajax({
        url: "site-resources/api/update_entry.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Application Updated Successfully");
                $("#acceptance").empty();
                Data.listAccepted(uid);
                $(".modal").remove();
                $(".filter-page").fadeOut();
            } else {
                alert("There was an error:\n" + data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//rejects accepted applications
Data.dropEntry = function(id, uid) {
    var postData = {
        id: id
    };

    $.ajax({
        url: "site-resources/api/drop_entry.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Application Dropped Successfully");
                $("#acceptance").empty();
                Data.listAccepted(uid);
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//verifies accepted applications
Data.verifyEntry = function(id, uid) {
    var postData = {
        id: id
    };

    $.ajax({
        url: "site-resources/api/verify_entry.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Application Verified Successfully");
                $("#acceptance").empty();
                Data.listAccepted(uid);
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

Data.verifyAssignment = function(vid) {
    var postData = {
        vid: vid
    };

    $.ajax({
        url: "site-resources/api/verify_assignment.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Assignment Confirmation Successful");
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//rejects accepted applications
Data.rejectEntry = function(id) {
    var postData = {
        id: id
    };

    $.ajax({
        url: "site-resources/api/reject_entry.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Entry Rejected Successfully");
                $("#acceptance").empty();
                Data.listAccepted();
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Deletes an entry from the database
Data.deleteEntry = function(id) {
    var postData = {
        id: id
    };

    $.ajax({
        url: "site-resources/api/delete_entry.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Entry Deleted Successfully");
                $("#applications").empty();
                Data.listPending();
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Accepts an application that is pending
Data.acceptEntry = function(id) {
    var postData = {
        id: id
    };

    $.ajax({
        url: "site-resources/api/accept_entry.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Entry Accepted Successfully");
                $("#applications").empty();
                Data.listPending();
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Checks if a user has any unread messages
Data.checkMessages = function(uid) {
    var postData = {
        uid: uid
    };

    $.ajax({
        url: "site-resources/api/check_messages.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data > 0) {
                $("#messages").addClass("attention").text("Unread Messages: " + data);
            } else {
                if ($("#messages").hasClass("attention") == true) {
                    $("#messages").removeClass("attention");
                }
            }
        },
        error: function(data) {
            console.log("error");
        }
    });
}

//Lists a users messages
Data.listMessages = function(uid) {
    var postData = {
        uid: uid
    };
    App.loading($(".content"));
    $.ajax({
        url: "site-resources/api/list_messages.php",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(data) {
            $(".loading").remove();
            $("<button type = 'submit' id = 'writem'>Message Program Director</button>").remove().appendTo("#main_ting").off("click").click(function() {
                App.createMessage(1, uid, "Subject", "");
            });
            var header = $("<tr id = 'fixed'><th>Timestamp</th><th>Subject</th><th>Sender</th><th>Action</th></tr>");
            $("#message-div").append(header);
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    //Data.getUser("id",data[i]["source"]);
                    var message = data[i]["message"].replace("'", "&#39;");
                    var message = message.replace('"', "&#34;");

                    var cells = "<td>" + data[i]['timestamp'] + "</td><td><a href = '#' class = 'read' value = '" + message + "' data-id = '" + data[i]["mid"] + "' data-read='" + data[i]["status"] + "'>" + data[i]['subject'] + "</a></td><td>" + data[i]['source'] + "</td><td><select class = 'action' id = '" + data[i]['mid'] + "' data-from='" + data[i]["sourceid"] + "' data-subject='" + data[i]["subject"] + "'><option>Action</option><option>Delete</option><option>Reply</option></select></td>";

                    if (data[i]["status"] == "Unread") {
                        var div = $("<tr class = 'attention-message'>" + cells + "</tr>");
                    } else {
                        var div = $("<tr>" + cells + "</tr>");
                    }
                    $("#message-div").append(div);

                }
                $('.read').click(function() {
                    var mid = $(this).attr("value");
                    var sub = $(this).text();
                    var id = $(this).attr("data-id");
                    var read = $(this).attr("data-read");

                    App.createModal(sub, mid);

                    if (read == "Unread") {
                        Data.readMessage(id, uid);
                    }
                });

                $(".action").off("change").on("change", function() {
                    var c = confirm("Confirm Action: " + $(this).val());
                    var sub = $(this).attr("data-subject");
                    var from = $(this).attr("data-from");
                    if (c == true) {
                        if ($(this).val() == "Delete") {
                            Data.deleteMessage($(this).attr("id"), uid);
                        } else if ($(this).val() == "Reply") {
                            var subject = "Re:" + sub;
                            App.createMessage(from, uid, subject);
                        } else {
                            alert("Unknown Request");
                        }
                    }
                });
            } else {
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Marks a message as being read
Data.readMessage = function(mid, uid) {
    var postData = {
        mid: mid
    };

    $.ajax({
        url: "site-resources/api/read_message.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                $("#message-div").empty();
                Data.listMessages(uid);
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Deletes a message
Data.deleteMessage = function(mid, uid) {
    postData = {
        mid: mid
    };

    $.ajax({
        url: "site-resources/api/delete_message.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                $("#message-div").empty();
                Data.listMessages(uid);
            } else {
                alert("There was an error... oops");
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Creates a message
Data.writeMessage = function(dest, src, subject, message) {
    postData = {
        destination: dest,
        source: src,
        subject: subject,
        message: message
    };

    $.ajax({
        url: "site-resources/api/write_message.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Your message was send successfully!");
                $(".modal").remove();
                $(".filter-page").fadeOut();
            } else {
                alert("There was an error: " + data);
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Lists all programs that have not been completed.
Data.listPrograms = function(table) {
    var postData = {
        flag: true
    };
    App.loading($(".content"));
    $.ajax({
        url: "site-resources/api/list_programs.php",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(data) {
            $(".loading").remove();
            var header = $("<tr id = 'fixed'><th>Program ID</th><th>Program Name</th><th>Program Age</th><th>Limit</th><th>Start</th><th>End</th><th>Registered</th><th>Actions</th></tr>");
            table.append(header);

            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var row = $("<tr><td>" + data[i]["pid"] + "</td><td>" + data[i]["name"] + "</td><td>" + data[i]["age"] + "</td><td>" + data[i]["size"] + "</td><td>" + data[i]["start"] + "</td><td>" + data[i]["end"] + "</td><td>" + data[i]["registered"] + "</td><td><select class = 'program-action' data-pid = '" + data[i]['pid'] + "' data-size = '" + data[i]["size"] + "'><option>Select an Action</option><option>View Roster</option><option>View Groups</option><option>Create Groups</option><option>Delete Program</option></select></td></tr>");

                    row.appendTo(table);
                }
                $(".program-action").on("change", function() {
                    var pid = $(this).attr("data-pid");
                    if ($(this).val() == "View Roster") {
                        Data.listRoster(pid);
                    } else if ($(this).val() == "Delete Program") {
                        var p = confirm("Are you sure you wish to delete this program?");
                        if (p == true) {
                            alert("Program Deleted");
                        }
                    } else if ($(this).val() == "Create Groups") {
                        var groupno = prompt("How many groups for this program?");
                        if (groupno === null) {
                            alert("Action Cancelled");
                        } else {
                            var maxno = $(this).attr("data-size") / groupno;
                            var maxnoro = Math.ceil(maxno);
                            for (var i = 1; i <= groupno; i++) {
                                Data.createProgramGroup($(this).attr("data-pid"), i, maxnoro, groupno);
                            }
                        }
                    } else if ($(this).val() == "View Groups") {
                        Data.listGroups(pid);
                    }
                });
            } else {
                alert("No programs found");
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Creates a new programs
Data.newProgram = function() {
    var prompt = "<b>Program Type: </b><select id = 'type'><option>Academy</option><option>Eclipse</option><option>League</option><option>Flint</option></select><br><br><b>Program Age Group: </b><select id = 'group'><option>PALBOTICS Boost</option><option>PALBOTICS Prime</option></select><br><br><b>Capacity: </b><input type = 'number' class  = 'small-input' id = 'capacity' required><br><br><b>Start Date: </b> <input type = 'text' id = 'starting' class = 'date small-input'><br><br><b>End Date: </b> <input type = 'text' id = 'ending' class = 'date small-input'><br><button type = 'submit' class = 'back' id = 'create-program' style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Create Program</button>";

    App.createModal("Create a New Program", prompt);
    $(".date").datepicker();
    $(".ui-datepicker").draggable();
    $(".ui-datepicker-title").css("cursor", "move");

    $("#create-program").off("click").on("click", function() {
        var postData = App.cycle($(".modal-content"));
        $.ajax({
            url: "site-resources/api/create_program.php",
            data: postData,
            type: "POST",
            success: function(data) {
                if (data == "success") {
                    $(".modal").remove();
                    $(".filter-page").fadeOut();
                    alert("New Program Created");
                    $("#program-data").empty();
                    Data.listPrograms($("#program-data"));
                } else {
                    alert("Error: " + data);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
}

//lists roster for specified program in a modal
Data.listRoster = function(pid) {
    var postData = {
        pid: pid
    };
    $.ajax({
        url: "site-resources/api/get_roster.php",
        data: postData,
        type: "POST",
        dataType: "json",
        success: function(data) {
            if (data.length > 0) {
                //console.log(data);
                var containering = "<h3>Participants</h3><table class = 'm-participant'><th>User ID</th><th>First</th><th>Last</th><th>Group</th></table><br><h3>Mentors</h3><table class = 'm-mentor'><th>Mentor</th><th>First</th><th>Last</th><th>Group</th></table>";
                App.createModal("Program Roster", containering);
                var maxno = Number(data["lengthy"]);
                var optionso = "";
                for (var i = 1; i <= data[0]["groupings"]; i++) {
                    optionso += "<option>Group " + i + "</option>";
                }
                for (var i = 0; i < maxno; i++) {
                    var string = "<tr><td>" + data[i]['uid'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td><select class = 'groupings' id = '" + data[i]['uid'] + "' data-role = '" + data[i]["role"] + "'><option>Choose a Group</option>" + optionso + "</select></td></tr>";
                    var entry = $(string);
                    entry.appendTo($(".m-" + data[i]["role"])).on("click", {
                        arg1: data[i]
                    }, function(e) {
                        var second = e.data.arg1;
                        if (second["role"] != "mentor") {
                            App.createInfoModal(second);
                        }
                    }).css("cursor", "pointer").find("select").click(function() {
                        return false;
                    });
                }
                $(".groupings").on("change", function() {
                    var uid = $(this).attr("id");
                    var role = $(this).attr("data-role");
                    var val = $(this).val().replace("Group ", "");
                    if (val != "Choose a Group") {
                        Data.assignGroup(uid, val, pid, role);
                    }
                });
            } else {
                alert("Error: Empty Set");
                console.log(data);
            }
        },
        error: function(data) {
            alert("Error: " + data.responseText);
            console.log(data);
        }
    });
}

//list group roster for a program
Data.listGroups = function(pid) {
    var postData = {
        pid: pid
    };
    $.ajax({
        url: "site-resources/api/get_groups.php",
        data: postData,
        type: "POST",
        dataType: "json",
        success: function(data) {
            if (data.length > 0) {
                //console.log(data);
                var containering = "";
                //alert(data[0]["groupings"]);
                for (var i = 1; i <= data[0]["groupings"]; i++) {
                    containering += "<h3>Group " + i + "</h3><table class = 'm-group-" + i + "'><th>User ID</th><th>First</th><th>Last</th><th>Role</th></table><br>"
                }

                App.createModal("Program Group Roster", containering);
                for (var i = 0; i < data.length; i++) {
                    var entry = $("<tr><td>" + data[i]['uid'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td>" + data[i]["role"] + "</td></tr>");
                    entry.appendTo($(".m-group-" + data[i]["section"]));
                }
            } else {
                alert("Error: " + data);
                console.log(data);
            }
        },
        error: function(data) {
            alert("Error: " + data.responseText);
            console.log(data);
        }
    });
}

//list all registered mentors
Data.listMentors = function() {
    var postData = {
        flag: true
    };
    App.loading($(".content"));
    $.ajax({
        url: "site-resources/api/list_mentors.php",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(data) {
            $(".loading").remove();
            var header = $("<tr id = 'fixed'><th>Mentor ID</th><th>First Name</th><th>Last Name</th><th>Grade</th><th>Shirt</th><th>Phone</th><th>Email</th><th>Program</th><th>Age Group</th><th>Session</th><th>Group</th><th>Status</th><th>Action</th></tr>");
            $("#mentors_apps").append(header);
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var cells = "<td>" + data[i]['id'] + "</td><td>" + data[i]['first'] + "</td><td>" + data[i]['last'] + "</td><td>" + data[i]['grade'] + "</td><td>" + data[i]['shirt'] + "</td><td>" + data[i]['phone'] + "</td><td>" + data[i]['email'] + "</td><td>" + data[i]['program'] + "</td><td>" + data[i]['group'] + "</td><td>" + data[i]['program_time'] + "</td><td>" + data[i]["gid"] + "</td><td>" + data[i]["status"] + "</td><td><select class = 'action' data-pid = '" + data[i]['pid'] + "' id = '" + data[i]['id'] + "'><option>Action</option><option>Drop</option><option>Assign Program</option><option>Assign Group</option></select></td>";

                    var div = $("<tr>" + cells + "</tr>");
                    $("#mentors_apps").append(div);
                }
                $(".action").on("change", function() {
                    var c = confirm("Confirm Action: " + $(this).val());

                    if (c == true) {
                        if ($(this).val() == "Drop") {
                            Data.dropMentor($(this).attr("id"));
                        } else if ($(this).val() == "Assign Program") {
                            var pida = $(this).attr("data-pid");
                            var programa = prompt("Please enter a program id", pida);
                            if (programa === null) {
                                alert("Action Cancelled");
                            } else {
                                Data.assignMentorProgram(programa, $(this).attr("id"));
                            }
                        } else if ($(this).val() == "Assign Group") {
                            var groupa = prompt("Please enter a group id");
                            if (groupa === null) {
                                alert("Action Cancelled");
                            } else {
                                Data.assignMentorGroup($(this).attr("id"), groupa);
                            }
                        }
                    }
                });
            } else {
                $(".content").empty().append($("<div>NO MENTORS</div>"))
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//assign a mentor to a program
Data.assignMentorProgram = function(pid, vid) {
    var postData = {
        vid: vid,
        pid: pid
    };

    $.ajax({
        url: "site-resources/api/assign_mentor.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Mentor Assigned Successfully");
                $("#mentors_apps").empty();
                Data.listMentors();
            } else {
                alert("Unable to complete action: " + data);
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

Data.checkActiveAssignments = function(uid, option, callback) {
    var date;

    switch (option) {
        case "in-progress":
            date = 1;
            break;
        case "active":
            date = 2;
            break;
        default:
            date = -1;
    }

    postData = {
        uid: uid,
        date: date
    };

    $.ajax({
        url: "site-resources/api/check_active.php",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(data) {
            //console.log(data);
            callback(data, uid);
        },
        error: function(data) {
            alert("Error: " + data.responseText);
        }
    });
}

Data.submitMission = function(uid) {

}

Data.viewMission = function(uid) {

}

//manage team assignment
Data.manageTeam = function(data, uid) {

    App.loading($(".content"));
    if (data[0] == undefined) {
        $(".loading").remove();
        //alert("No active assignments to manage");
        $(".content").append($("<div>No active teams</div>"));
    } else {
        var postData = {
            pid: data[0],
            uid: uid
        };
        $.ajax({
            url: "site-resources/api/manage_teams.php",
            data: postData,
            dataType: "json",
            type: "POST",
            success: function(data) {
                console.log(data);
                $(".loading").remove();
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        if (typeof data[i]["participants"][0] == "undefined") {
                            var message = $("<div>Program: <b>" + data[i]["name"] + "</b><br>Age Level: <b>" + data[i]["age"] + "</b><br>Session: <b>" + data[i]["start"] + "</b><br>NO PARTICIPANTS ASSIGNED TO YOUR GROUP YET</div><hr><br>");
                        } else {
                            var string = "";
                            var message = $("<div>Program: <b>" + data[i]["name"] + "</b><br>Age Level <b>" + data[i]["age"] + "</b><br>Session: <b>" + data[i]["start"] + "</b><br><br><table id = 'mem'><tr><th>First Name</th><th>Last Name</th><th>Gender</th><th>Grade</th></tr></table><br><br><table id = 'tem'><tr><th>Team Number</th><th>Team Name</th><th>Sponsors</th><th>Logo</th><th>Slogan</th><th>Budget</th></tr></table><button type = 'submit' class = 'back' id = 'mission' data-app = " + data[i]["gid"] + " style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Submit Mission</button><hr><br> </div>");

                            message.appendTo($("#manage_teams"));
                            $("#mission").on("click", {
                                arg1: data[i]
                            }, function(e) {
                                var first = e.data.arg1;
                                Data.getActiveMission(first["gid"]);
                            });

                            for (var e = 0; e < data[i]["participants"]["count"]; e++) {
                                $("<tr><td>" + data[i]["participants"][e]["first"] + "</td><td>" + data[i]["participants"][e]["last"] + "</td><td>" + data[i]["participants"][e]["gender"] + "</td><td>" + data[i]["participants"][e]["grade"] + "</td></tr>").appendTo($("#mem")).on("click", {
                                    arg1: data[i]["participants"][e]
                                }, function(e) {
                                    var second = e.data.arg1,
                                        strings;
                                    strings = "<b>Commendation Name:</b><br> <input type = 'text' id = 'name' class = 'small-input' placeholder = 'Hardworker' required><br><br><b>Commendation Description:</b><br> <textarea id = 'explain' style = 'font-family:inherit;height:300px;width:500px;padding:5px;' required>For working hard all of the time</textarea><br><br><b>Nominate for PALBOTICS Award?</b>  Please write a brief description as to why you think they should receive this award.<br><textarea id = 'special' style = 'font-family:inherit;height:300px;width:500px;padding:5px;' required>They da best</textarea><br><br><button type = 'submit' class = 'back' id = 'submit-award' data-app = " + second["uid"] + " style = 'margin-top:20px;margin-bottom:0;position:relative;left:50%;transform:translate(-50%,-20px);'>Save Changes</button>";

                                    App.createModal("Award Page: " + second["first"] + " " + second["last"], strings);

                                    $("#submit-award").on("click", function() {
                                        var postData = {
                                            name: $("#name").val(),
                                            explain: $("#explain").val(),
                                            special: $("#special").val(),
                                            uid: $("#submit-award").attr("data-app")
                                        };
                                        $.ajax({
                                            url: "site-resources/api/submit_award.php",
                                            type: "POST",
                                            data: postData,
                                            success: function(data) {
                                                console.log(data);
                                                if (data == "success") {
                                                    alert("Award submitted successfully");
                                                } else {
                                                    alert("Award submission failed: " + data);
                                                }
                                            },
                                            error: function(data) {
                                                alert("real error: " + data);
                                                console.log(data);
                                            }
                                        });
                                    });
                                }).css("cursor", "pointer");
                            }
                            var stringt = "";
                            $("<tr class = 'team-data' style = 'cursor:pointer;'><td>" + data[i]["gid"] + "</td><td>" + data[i]["team_name"] + "</td><td>" + data[i]["sponsors"] + "</td><td><image src = '" + data[i]["logo"] + "' style = 'width:50px;'</td><td>" + data[i]["slogan"] + "</td><td>$" + data[i]["budget"] + "</td></tr>").appendTo($("#tem"));

                            $(".team-data").on("click", {
                                arg1: data[i]
                            }, function(e) {
                                var first = e.data.arg1;
                                var modalcon = "<b>Team Name: </b><input type = 'text' value = '" + first['team_name'] + "' id = 'team_name' class = 'small-input' placeholder = 'Team Name' required><br><b>Team Sponsors (seperate with commas): </b><input type = 'text' value = '" + first['sponsors'] + "' id = 'sponsors' class = 'small-input' placeholder = 'Team Sponsors' required><br><b>Team Logo: </b><input type = 'file' id = 'team_logo' accept='.jpg,.png,.gif,.jpeg' name = 'logod' required><label for = 'team_logo'>Upload File</label> <span class = 'check' style = 'color:green;display:none;font-size:20px;'>&#10004;</span><br><b>Team Slogan: </b><input type = 'text' value = '" + first['slogan'] + "' id = 'slogan' class = 'small-input' placeholder = 'Team Slogan' required><br><b>Team Budget (only update this when sponsorships are earned): </b><input type = 'text' value = '" + first['budget'] + "' id = 'budget' class = 'small-input' placeholder = 'Team Budget' required><br><br><button type = 'submit' class = 'back' id = 'submit-team-data' data-app = " + first["gid"] + " style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Submit Changes</button>";

                                App.createModal("Team Editor", modalcon);

                                var files;

                                $("#team_logo").on("change", function(event) {
                                    $(".check").show();
                                    files = event.target.files;
                                });
                                //alert($("#submit-team-data").text());
                                $("#submit-team-data").on("click", function() {

                                    var postData = new FormData();
                                    postData.append("gid", $(this).attr("data-app"));
                                    postData.append("logo", files);
                                    postData.append("name", $("#team_name").val());
                                    postData.append("sponsors", $("#sponsors").val());
                                    postData.append("slogan", $("#slogan").val());
                                    postData.append("budget", $("#budget").val());

                                    if ((files == null) || (typeof files.length == "undefined")) {

                                    } else {
                                        $.each(files, function(key, value) {
                                            postData.append(key, value);
                                        });

                                    }

                                    $.ajax({
                                        url: "site-resources/api/update_team.php",
                                        type: "POST",
                                        data: postData,
                                        cache: false,
                                        processData: false,
                                        contentType: false,
                                        success: function(data) {
                                            if (data == "success") {
                                                alert("Changes made successfully");
                                                $(".content").empty();
                                                Data.manageTeam(data, uid);
                                            } else {
                                                console.log(data);
                                                alert("Failure: " + data);
                                            }
                                        },
                                        error: function(data) {
                                            alert("Failure: " + data);
                                            console.log(data);
                                        }
                                    })
                                });
                            });
                        }

                    }
                } else {
                    console.log(data);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
}

//drop a mentor from the program
Data.dropMentor = function(vid) {
    alert("Mentor Dropped");
}


//assign a mentor to a group
Data.assignGroup = function(uid, section, pid, role) {
    var postData = {
        uid: uid,
        section: section,
        pid: pid,
        role: role
    };

    $.ajax({
        url: "site-resources/api/assign_group.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                alert("Group Assigned Successfully");
            } else {
                alert("Error: " + data);
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//create groups within programs
Data.createProgramGroup = function(pid, gid, max, last) {
    var postData = {
        pid: pid,
        gid: gid,
        max: max
    };

    $.ajax({
        url: "site-resources/api/create_groups.php",
        type: "POST",
        data: postData,
        success: function(data) {
            if (data == "success") {
                if (gid == last) {
                    alert("Action Complete");
                }
            } else {
                alert("There was an error creating section " + gid + ": " + data);
                console.log(data);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Lists mentor assignment
Data.viewAssignment = function(uid) {

    var div = $("<div class = 'info-square'></div>");

    div.appendTo($("#program_assignment"));

    var postData = {
        uid: uid
    };

    $.ajax({
        url: "site-resources/api/view_assignment.php",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(data) {
            //console.log(data);
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    if (typeof data[i]["participants"][0] == "undefined") {
                        var message = $("<div>Program: <b>" + data[i]["name"] + "</b><br>Age Level: <b>" + data[i]["age"] + "</b><br>Session: <b>" + data[i]["start"] + "</b><br>NO PARTICIPANTS ASSIGNED TO YOUR GROUP YET</div><hr><br>");
                    } else {
                        var string = "";
                        for (var e = 0; e < data[i]["participants"]["count"]; e++) {
                            string += "<tr><td>" + data[i]["participants"][e]["first"] + "</td><td>" + data[i]["participants"][e]["last"] + "</td><td>" + data[i]["participants"][e]["gender"] + "</td><td>" + data[i]["participants"][e]["grade"] + "</td></tr>";
                        }
                        var message = $("<div>Program: <b>" + data[i]["name"] + "</b><br>Age Level <b>" + data[i]["age"] + "</b><br>Session: <b>" + data[i]["start"] + "</b><br><br><table><tr><th>First Name</th><th>Last Name</th><th>Gender</th><th>Grade</th></tr>" + string + "</table><button type = 'submit' class = 'back' id = 'confirmation' data-app = " + data[i]["vid"] + " style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>Confirm Assignment</button><hr><br> </div>");
                    }
                    message.appendTo($(".info-square"));
                }
            } else {
                $(".info-square").empty();
                $(".info-square").html("No assignment posting found");
            }
            $("#confirmation").click(function() {
                Data.verifyAssignment($(this).attr("data-app"));;
            });
        },
        error: function(data) {
            alert("Error: " + data.responseText);
            console.log(data);
        }
    });
}

Data.getActiveMission = function(gid) {
    var postData = {
        gid: gid
    };
    $.ajax({
        url: "site-resources/api/get_mission.php",
        data: postData,
        type: "POST",
        dataType: "json",
        success: function(data) {
            if (data.length > 0) {
                App.createModal("Mission " + data[0]["progression"], "<u>Objective:</u> <p>" + data[0]["objective"] + "</p><b>Points Awarded for Completion: " + data[0]["value"] + "<br><br><input type = 'file' id = 'mission-goal' name = 'mission-goal' /><label for = 'mission-goal'>Submit Completion Proof</label>");

                var files;

                $("#mission-goal").off("change").on("change", {
                    arg: data[0]
                }, function(e) {
                    var first = e.data.arg;
                    files = e.target.files;
                    var postData = new FormData();
                    $.each(files, function(key, value) {
                        postData.append(key, value);
                    });
                    postData.append('value', first['value']);
                    postData.append('progression', first['progression']);
                    postData.append('moid', first['moid']);
                    postData.append('gid', first['gid']);

                    $.ajax({
                        url: "site-resources/api/submit_mission.php",
                        type: "POST",
                        data: postData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data == "success") {
                                alert("Mission Completed!");
                                $(".modal").remove();
                                $(".filter-page").hide();
                            } else {
                                alert("Error: " + data);
                                console.log(data);
                            }
                        },
                        error: function(data) {
                            alert("Error real: " + JSON.stringify(data));
                        }
                    });

                });
            } else {
                alert("Error" + data);
            }
        },
        error: function(data) {
            alert("Error: " + data.responseText);
            console.log(data);
        }
    });
}

//Shows past programs associated with the user's idea. For an id of -1 (a lead mentor), shows all past programs, callback with data, program parameters
Data.reviewPrograms = function(uid, callback) {
    var postData = {
        uid: uid
    };
    console.log(uid);
    $.ajax({
        url: "site-resources/api/get_past_programs.php",
        data: postData,
        dataType: "json",
        type: "POST",
        success: function(data) {
            callback(data);
        },
        error: function(data) {
            alert("Error: see logs");
            console.log(data);
        }
    });
}

Data.parentialReview = function(data) {
    var div, i, flag;
    console.log(data);
    for (i = 1; i <= data.length; i++) {
        div = $("<div class = 'info-square' style = ''><b>Program Name: </b>" + data[i].name + "<br><br><b>Program Age Group:</b> " + data[i].age + "<br><br><b>Start Date:</b> " + data[i].start + "<br><br><b>End Date:</b> " + data[i].end + "<br><br><b>Registered Participants:</b> " + data.length + "<br><button type = 'submit' class = 'back' id = 'see-images' data-app = " + data[i].id + " style = 'margin-top:20px;margin-bottom:0;position:relative;width:auto;'>View Program Photos</button></div>");

        div.appendTo($(".content"));

        $("#see-images").on("click", {
            arg1: data[i]
        }, function(e) {

            var first = e.data.arg1,
                postData;
            postData = {
                id: first.id,
                uid: User.id
            };
            $.ajax({
                url: "site-resources/api/get_past_program_images.php",
                type: "POST",
                dataType: "json",
                data: postData,
                success: function(data) {
                    var stringy, path, it = 0;

                    function secondWhen(firstly, d) {
                        //alert(d);
                        path = "site-resources/images/teams/" + firstly["gid"] + "/mission/" + firstly["mission"][d]["moid"] + "/";

                        var fileextension = ".jpg";
                        $.ajax({

                            url: path,
                            success: function(data) {
                                //List all .png file names in the page
                                $(data).find("a:contains(" + fileextension + ")").each(function() {
                                    var filename = this.href.replace(window.location.host, "").replace("http://", "").replace("/", "");
                                    //console.log(path+filename);
                                    $(".inner").append("<li><img src='" + path + filename + "' class = 'nu' /></li>");
                                    //  alert("G");

                                    //flag = false;
                                });
                                d++;
                                //it = d;
                                if (d < firstly["mission"].length) {
                                    secondWhen(firstly, d);
                                } else {
                                    $(".loading").remove();
                                    $(".image-div").show().bjqs({
                                        height: 500,
                                        width: 900,
                                        responsive: true,
                                        nexttext: ">",
                                        prevtext: "<",
                                        showmarkers: false
                                    });
                                    $(".modal-content").css({
                                        padding: 0
                                    });
                                    //alert($(".nu").length);

                                    //App.slideShow($(".image-div"));
                                }
                            }
                        });
                    }

                    console.log(data);
                    if (data.length > 0) {
                        var string = "Please select a participant to view their pictures: <br><div class = 'nameplate'></div>";
                        App.createModal("View Program Photos", string);
                        for (var i = 0; i < data.length; i++) {
                            var secondary = $("<button class = 'selected' style = 'background:none;height:50px;width:100px;border:2px solid black;cursor:pointer;margin-top:5px;margin-right:10px;'>" + data[i]["name"] + "</button>");
                            secondary.appendTo($(".nameplate")).on("click", {
                                arg1: data[i]
                            }, function(e) {
                                var first = e.data.arg1;
                                $(".modal, .filter").remove();
                                stringy = "";
                                path = '';
                                App.createModal("Pictures: " + first["name"], "<div class = 'image-div' id = 'my-slideshow'><ul class = 'inner bjqs'></ul></div>");
                                $(".image-div").css("display", "none");
                                //alert("d");
                                App.loading($(".modal-content"));
                                $(".loading").parent(".modal-content").css({
                                    textAlign: "center"
                                });
                                //alert("d");
                                secondWhen(first, 0);

                                //            $(".image-div").show().bjqs({height:320, width:600, responsive:true});
                            });
                        }
                    } else {
                        alert("We could not process your request at this time");
                    }
                },
                error: function(data) {
                    console.log(data);
                    alert("Error: " + data.responseText);
                }
            });
        });
    }
}

Data.addMission = function(objective, min) {

}
