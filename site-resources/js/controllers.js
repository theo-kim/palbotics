app.controller('loginController', loginController);
app.controller('menuController', menuController);
app.controller('accountController', accountController);
app.controller('pendingAppController', pendingAppController);
app.controller('acceptedAppController', acceptedAppController);
app.controller('programController', programController);
app.controller('mentorController', mentorController);
app.controller('mentorProgramController', mPController);
app.controller('missionController', missionController);
app.controller('programManageController', programManageController);
app.controller('groupController', groupController);
app.controller('assignGroupController', assignGroupController);
app.controller('manageTeamController', manageTeamController);
app.controller('viewMissionController', viewMissionController);
app.controller('awardTeamController', awardTeamController);

function loginController($scope, $rootScope, $http, $timeout, $httpParamSerializerJQLike, httpPro) {
    function checkUsername() {
        httpPro.getSuccess('site-resources/api/auth/username.php', {
                username: $scope.username
            })
            .then(() => {
                $scope.user = $scope.username;
                $scope.message = `Welcome, <b>${$scope.user}</b>, please enter your password.`
                $scope.checked = false;
                $scope.username = "";
                $scope.placeholder = "Password";
                $scope.type = "password";
                $scope.checkForm = checkPassword;
                $scope.passed = true;
                $scope.$apply();
            })
            .catch((err) => {
                $scope.error = "That username wasn't found.";
                $timeout(() => $scope.error = "", 2500);
                $scope.$apply();
            });
    };

    function checkPassword() {
        const url = '/site-resources/api/auth/login.php';
        const params = {
            username: $scope.user,
            password: $scope.username
        };
        httpPro.postSuccessPHP(url, params)
            .then(() => {
                fetchUserData();
            })
            .catch((err) => {
                $scope.error = "Sorry, incorrect password.";
                $scope.$apply();
                $timeout(() => $scope.error = "", 2500);
            });
    }

    function fetchUserData() {
        httpPro.getJSON('/site-resources/api/users/get.php', {})
            .then((res) => {
                $rootScope.Data = {};
                $rootScope.userData = res;
                $rootScope.logged = true;
                $scope.$apply();
            })
            .catch((err) => {
                console.log(err);
                $scope.error = "Something went wrong, please try again later";
                $scope.$apply();
                $timeout(() => $scope.error = "", 2500);
            });
    }

    function forgot() {
        $rootScope.loading = true;
        httpPro.postSuccessPHP('/site-resources/api/reset_password.php', {
                username: $scope.user,
            })
            .then(() => {
                $rootScope.loading = false;
                $scope.good = `An email with instructions to reset the
                    password has been sent to the email on file.
                    Please allow up to 10 minutes to receive it.`;
                $scope.$apply();
                $timeout(() => $scope.good = "", 2500);
            })
            .catch(() => {
                $rootScope.loading = false;
                $scope.error = "Sorry, something went wrong";
                $scope.$apply();
                $timeout(() => $scope.error = "", 2500);
            });
    }

    function check () {
        httpPro.getInt('/site-resources/api/auth/get_role.php', {})
            .then((res) => {
                $scope.passed = true;
                fetchUserData();
            })
            .catch((err) => {
                $rootScope.logged = false;
                $scope.passed = false;
            });
    }

    $scope.message = "Welcome to your myPALBOTICS portal, please enter your username below.";
    $scope.placeholder = "Username";
    $scope.type = "text";
    $scope.isUsername = function() {
        if ($scope.username.length > 0) $scope.checked = true;
        else $scope.checked = false
    }
    $scope.checkForm = checkUsername;
    $scope.forgotPassword = forgot;
    check();
}

function menuController($scope, $rootScope, $window, $location, $timeout, httpPro) {
    $rootScope.page = "myPALBOTICS";
    $scope.logout = function() {
        httpPro.postSuccessPHP('/site-resources/api/auth/logout.php', {})
            .then(() => {
                $rootScope.logged = false;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
            });
    }
    $scope.certificates = () => $window.open("./certificates.php");
    $scope.standings = () => $window.open("./standings.php");
    $scope.profile = () => $window.location.hash = "account";
    $scope.pending = () => $window.location.hash = "applications/pending";
    $scope.accepted = () => $window.location.hash = "applications/accepted";
    $scope.mentors = () => $window.location.hash = "mentors/manage";
    $scope.programs = () => $window.location.hash = "programs";
    $scope.manageTeams = () => $window.location.hash = "team/manage";
    $scope.vendors = () => $window.location.hash = "vendors";
    $scope.role = $rootScope.userData.role;
    $timeout(() => $rootScope.menu = true, 1000);
}

function accountController($scope, $rootScope, $timeout) {
    $rootScope.menu = false;
    $rootScope.page = "My Account";
    $timeout(function() {
        $rootScope.open = true;
        $scope.name = $rootScope.userData.first + " " + $rootScope.userData.last;
        $scope.role = $rootScope.userData.role;
        $scope.email = $rootScope.userData.email;
        $scope.joined = new Date($rootScope.userData.joined);
        $scope.username = $rootScope.userData.username;
    }, 1000)
}

function pendingAppController($scope, $rootScope, $timeout, httpPro) {
    function load() {
        httpPro.getJSONArray("site-resources/api/applications/pending/list.php", {
                uid: -1
            })
            .then((res) => {
                $scope.apps = res;
                $scope.$apply();
            })
            .catch((err) => alert("Something went wrong"));
    }
    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Pending Applications";
    $scope.apps = [];
    load();
}

function acceptedAppController($scope, $rootScope, $timeout, httpPro, redirect) {
    function load() {
        httpPro.getJSONArray('/site-resources/api/applications/accepted/list.php', {
                uid: -1
            })
            .then((data) => {
                $scope.apps = data;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong");
                console.error(err);
            });
    }
    $rootScope.menu = false;
    $timeout(function() {
        $rootScope.open = true;
        $rootScope.page = "Accepted Applications";
        load();
    }, 1000);
    $scope.group = function(id) {
        redirect('participants/' + id + '/groups', 'applications/accepted');
    }
}

function programController($scope, $rootScope, httpPro, redirect) {
    function load() {
        httpPro.getJSONArray('site-resources/api/list_programs.php', {
                flag: true
            })
            .then((res) => {
                $scope.apps = res;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong");
                console.error(err);
            });
    }
    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Programs";
    $scope.manage = function(id) {
        redirect("programs/" + id + "/manage", "programs");
    }
    load();
}

function mentorController($scope, $rootScope, $timeout, httpPro, redirect) {
    function load() {
        httpPro.getJSONArray('/site-resources/api/mentors/list.php', {
                flag: true
            })
            .then((res) => {
                $scope.apps = res;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Something went wrong...");
                console.error(err);
            });
    }

    function drop(id) {
        const confirm = prompt("Please confirm your action by typing DELETE in the prompt below:", "");
        if (confirm !== null && confirm === "DELETE") {
            $rootScope.loading = true;
            httpPro.postSuccessPHP("site-resources/api/drop_mentor.php", {
                    id: id
                })
                .then(() => {
                    $scope.apps = [];
                    load();
                })
                .catch((err) => {
                    alert("Oops, something went wrong...");
                    console.error(err);
                });
        } else {
            alert("Nevermind")
        }
    }

    function grouping(id, status) {
        if (status === 'Unassigned') return false;
        redirect('mentors/' + id + '/groups', 'mentors/manage');
    }

    function program(mentor) {
        redirect('mentors/' + mentor.id + '/programs', 'mentors/manage');
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Program Mentors";
    $scope.drop = drop;
    $scope.grouping = grouping;
    $scope.program = program;
    load();
}

function mPController($scope, $rootScope, $route, $http, $httpParamSerializerJQLike) {
    function load() {
        const request = {
            method: "GET",
            url: "/site-resources/api/programs/list.php",
            params: {
                flag: true
            }
        };

        const response = function(res) {
            const data = res.data;
            if (data.constructor === Array) {
                $scope.apps = data;
                console.log($scope.current);
            }
        };

        $http(request).then(response);
    }

    function getCurrent() {
        const request = {
            method: "GET",
            url: "site-resources/api/assign_mentor.php",
            params: {
                vid: $scope.mentorID
            }
        };

        const response = function(res) {
            const data = parseInt(res.data);
            if (typeof data !== "number") alert("Oops, something went wrong")
            else {
                $scope.current = data;
                load();
            }
        };

        $http(request).then(response);
    }

    function assign(id) {
        const url = "site-resources/api/assign_mentor.php";
        const params = $httpParamSerializerJQLike({
            pid: id,
            vid: $scope.mentorID
        })
        const response = function(res) {
            $rootScope.loading = false;
            const data = res.data;
            if (data === "Failed to update registration status") alert("Mentor assignment unchanged");
            else if (data !== "success") alert("Something went wrong.");
            else {
                $scope.current = id
            }
        }
        $rootScope.loading = true;
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
        $http.post(url, params).then(response);
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $scope.assign = assign;
    $scope.mentorID = $route.current.params.id;

    getCurrent();
}

function programManageController($scope, $rootScope, $route, httpPro, redirect) {
    function load() {
        httpPro.getJSON('site-resources/api/props/count.php', {
                pid: $scope.id
            })
            .then((res) => {
                $scope.groups = res.group;
                $scope.missions = res.mission;
                $scope.participants = res.registered;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function mission() {
        redirect("programs/" + $scope.id + "/manage/missions", "programs/" + $scope.id + "/manage");
    }

    function groupsm() {
        redirect("programs/" + $scope.id + "/manage/groups", "programs/" + $scope.id + "/manage");
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Program Manager";
    $scope.id = $route.current.params.id;

    $scope.mission = mission;
    $scope.groupsm = groupsm;
    load();
}

function missionController($scope, $rootScope, $route, httpPro) {
    function load() {
        httpPro.getJSONArray('site-resources/api/list_missions.php', {
                pid: $scope.id
            })
            .then((res) => {
                $scope.apps = res;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function newM() {
        if ($scope.apps.length === 0) {
            $scope.progression = 1;
        } else {
            $scope.progression = $scope.apps[$scope.apps.length - 1].progression + 1;
        }
        const params = {
            pid: $scope.id,
            progression: $scope.progression,
            objective: $scope.objective,
            value: $scope.value
        };
        httpPro.postIdPHP('site-resources/api/create_mission.php', params)
            .then((res) => {
                $rootScope.loading = false;
                $scope.apps.push({
                    objective: $scope.objective,
                    value: $scope.value,
                    moid: res,
                    progression: $scope.progression,
                    pid: $scope.id
                });
                $scope.objective = '';
                $scope.value = '';
                $scope.$apply();
            })
            .catch((err) => {
                $rootScope.loading = false;
                alert("Oops, something went wrong...");
                console.error(err);
            })
    }

    function drop(id) {
        var confirm = prompt("Please confirm your action by typing DELETE in the prompt below:", "");
        if (confirm !== null && confirm === "DELETE") {
            $rootScope.loading = true;
            httpPro.postSuccessPHP("site-resources/api/drop_mission.php", {
                    id: id
                })
                .then(() => {
                    $rootScope.loading = false;
                    $scope.apps = [];
                    $scope.$apply();
                    load();
                })
                .catch((err) => {
                    $rootScope.loading = false;
                    alert("Something went wrong...");
                    console.error(err);
                });
        } else {
            alert("Nevermind");
        }
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Mission Manager";
    $scope.id = $route.current.params.id;
    $scope.newM = newM;
    $scope.drop = drop;
    load();
}

function groupController($scope, $rootScope, $route, httpPro) {
    function load() {
        httpPro.getJSONArray("site-resources/api/get_groups.php", {
                pid: $scope.id
            })
            .then((data) => {
                $scope.apps = []
                for (let i = 0; i < data.length; i++) {
                    if ($scope.apps[data[i].section - 1]) {
                        if (data[i].role === 'participant') $scope.apps[data[i].section - 1].members.push(data[i])
                        else if (data[i].role === 'mentor') $scope.apps[data[i].section - 1].mentors.push(data[i])
                    } else {
                        $scope.apps[data[i].section - 1] = {}
                        $scope.apps[data[i].section - 1].members = []
                        $scope.apps[data[i].section - 1].mentors = []
                        if (data[i].pid) {} else if (data[i].role === 'mentor') $scope.apps[data[i].section - 1].mentors.push(data[i])
                        else if (data[i].role === 'participant') $scope.apps[data[i].section - 1].members.push(data[i])
                        $scope.apps[data[i].section - 1].gid = data[i].gid
                        $scope.apps[data[i].section - 1].name = data[i].name
                        $scope.apps[data[i].section - 1].slogan = data[i].slogan
                        $scope.apps[data[i].section - 1].logo = data[i].logo
                    }
                }
                $rootScope.open = true;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function dropM(gid, uid, r, m, g) {
        var confirm = prompt("Please confirm your action by typing DELETE in the prompt below:", "");
        if (confirm !== null && confirm === "DELETE") {
            $rootScope.loading = true;
            httpPro.postSuccessPHP("site-resources/api/drop_group_member.php", {
                    gid: gid,
                    id: uid
                })
                .then(() => {
                    $rootScope.loading = false;
                    if (r === "mentor") {
                        const qi = $scope.apps.indexOf(g);
                        $scope.apps[qi].mentors.splice($scope.apps[qi].mentors.indexOf(m), 1);
                    } else {
                        const qi = $scope.apps.indexOf(g);
                        $scope.apps[qi].members.splice($scope.apps[qi].members.indexOf(m), 1);
                    }
                    $scope.$apply();
                })
                .catch((err) => {
                    $rootScope.loading = false;
                    $scope.$apply();
                    alert("Oops, something went wrong...");
                    console.error(err);
                });
        } else {
            alert("Nevermind");
        }
    }

    function drop(id) {
        var confirm = prompt("Please confirm your action by typing DELETE in the prompt below:", "");
        if (confirm !== null && confirm === "DELETE") {
            $rootScope.loading = true;
            httpPro.postSuccessPHP("site-resources/api/drop_group.php", {
                    gid: id
                })
                .then(() => {
                    $rootScope.loading = false;
                    $scope.apps = [];
                    $scope.$apply();
                    load();
                })
                .catch((err) => {
                    $rootScope.loading = false;
                    alert("Oops, something went wrong...");
                    console.error(err);
                });
        } else {
            alert("Nevermind")
        }
    }

    function addGroup() {
        $rootScope.loading = true;
        const params = $httpParamSerializerJQLike({
            section: $scope.apps.length + 1,
            max: 10,
            pid: $scope.id
        });
        httpPro.postSuccessPHP("site-resources/api/create_groups.php", params)
            .then(() => {
                $scope.apps = [];
                $scope.$apply();
                load();
            })
            .catch((err) => {
                $rootScope.loading = false;
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Group Manager";
    $scope.id = $route.current.params.id;
    $scope.dropM = dropM;
    $scope.drop = drop;
    $scope.addGroup = addGroup;
    load();
}

function assignGroupController($scope, $rootScope, $route, $http, $httpParamSerializerJQLike) {
    function groupLoad(id) {
        const request = {
            method: "GET",
            url: "site-resources/api/get_groups.php",
            params: {
                pid: id
            }
        };
        const response = function(res) {
            const data = res.data;
            if (data.constructor === Array) {
                $scope.apps = []
                for (let i = 0; i < data.length; i++) {
                    if ($scope.apps[data[i].section - 1]) {
                        if (data[i].role === 'participant') $scope.apps[data[i].section - 1].members.push(data[i])
                        else if (data[i].role === 'mentor') $scope.apps[data[i].section - 1].mentors.push(data[i])
                        if (data[i].uid === parseInt($scope.id) && data[i].role === $scope.role) {
                            $scope.apps[data[i].section - 1].active = true;
                            $scope.active = data[i].section - 1;
                        }
                    } else {
                        $scope.apps[data[i].section - 1] = {}
                        $scope.apps[data[i].section - 1].members = []
                        $scope.apps[data[i].section - 1].mentors = []
                        if (data[i].role === 'mentor') $scope.apps[data[i].section - 1].mentors.push(data[i])
                        else if (data[i].role === 'participant') $scope.apps[data[i].section - 1].members.push(data[i])
                        $scope.apps[data[i].section - 1].gid = data[i].gid
                        $scope.apps[data[i].section - 1].name = data[i].name
                        $scope.apps[data[i].section - 1].slogan = data[i].slogan
                        $scope.apps[data[i].section - 1].logo = data[i].logo
                        $scope.apps[data[i].section - 1].section = data[i].section
                        if (data[i].uid === parseInt($scope.id) && data[i].role === $scope.role) {
                            $scope.active = data[i].section - 1;
                            $scope.apps[data[i].section - 1].active = true;
                        }

                    }
                }
                console.log($scope.apps);
            }
            $rootScope.open = true;
        };

        $http(request).then(response);
    }

    function load() {
        const request = {
            method: "GET",
            url: "site-resources/api/get_pid.php",
            params: {
                id: $scope.id,
                role: $scope.role
            }
        };
        const response = function(res) {
            const data = parseInt(res.data);
            if (typeof data === "number") {
                $scope.pid = data;
                groupLoad(data);
            }
            $rootScope.open = true;
        };

        $http(request).then(response);
    }

    function assign(section) {
        $rootScope.loading = true;
        const url = "site-resources/api/assign_group.php";
        const params = $httpParamSerializerJQLike({
            section: section,
            role: $scope.role,
            pid: $scope.pid,
            uid: $scope.id
        });
        console.log(params)
        const response = function(res) {
            $rootScope.loading = false;
            const data = res.data;
            if (data !== "success") alert("Something went wrong.");
            else {
                $scope.apps[section - 1].active = true;
                $scope.apps[$scope.active].active = false;
            }
        }

        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
        $http.post(url, params).then(response);
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Assign Group";
    $scope.id = $route.current.params.id;
    $scope.role = $route.current.$$route.role;
    load();

    $scope.assign = assign;
}

function manageTeamController($scope, $rootScope, $route, httpPro, redirect, $timeout) {
    function groupInfo() {
        httpPro.getJSONArray('site-resources/api/manage_teams.php', {
                gid: $scope.gid
            })
            .then((res) => {
                $scope.group = res[0];
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function load() {
        httpPro.getJSON('site-resources/api/get_mentor.php', {
                uid: $rootScope.userData.id
            })
            .then((res) => {
                $scope.gid = res.gid;
                groupInfo();
                $rootScope.open = true;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function mission() {
        redirect("team/" + $scope.gid + "/missions", "team/manage");
    }

    function edit() {
        $rootScope.loading = true;
        const params = {
            gid: $scope.group.gid,
            name: $scope.group["team_name"],
            sponsors: $scope.group.sponsors,
            budget: $scope.money,
            slogan: $scope.group.slogan
        };
        httpPro.postSuccessPHP("site-resources/api/update_team.php", params)
            .then((res) => {
                $rootScope.loading = false;
                $scope.$apply();
            })
            .catch((err) => {
                $rootScope.loading = false;
                $scope.$apply();
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function awards() {
        redirect('team/' + $scope.gid + '/awards', 'team/manage');
    }
    $rootScope.menu = false;
    $rootScope.page = "Team Manager";
    $scope.mission = mission;
    $scope.edit = edit;
    $scope.awards = awards;
    $scope.num = (n) => {
        return parseInt(n)
    };
    $timeout(load, 1000);
}

function awardTeamController($scope, $rootScope, $route, httpPro) {
    function load() {
        httpPro.getJSONArray('site-resources/api/get_awards.php', {
                gid: $scope.gid
            })
            .then((res) => {
                $scope.apps = res;
                $scope.open = true;
                $scope.$apply();
            })
            .catch((err) => {
                alert("Oops, something went wrong...");
                console.error(err);
            });
    }

    function submit(award) {
        if (!award.name || !award.explaination) {
            alert("Please fill out all fields");
            return false;
        }
        $rootScope.loading = true;
        const params = {
            name: award.name,
            explain: award.explaination,
            uid: award.id
        }
        console.log(award);
        httpPro.postSuccessPHP('site-resources/api/submit_award.php', params)
            .then(() => {
                $rootScope.loading = false;
                $scope.$apply();
            })
            .catch((err) => {
                $rootScope.loading = false;
                $scope.$apply();
                alert("Oops, something went wrong");
                console.error(err);
            });
    }
    $rootScope.menu = false;
    $rootScope.page = "Award Submission";
    $scope.gid = $route.current.params.id;
    $scope.submit = submit;
    load();
}

function viewMissionController($scope, $rootScope, $route, $http, $httpParamSerializerJQLike) {
    function load() {
        const request = {
            method: "GET",
            url: "site-resources/api/get_mission.php",
            params: {
                gid: $scope.id
            }
        };

        const response = function(res) {
            const data = res.data;
            console.log(res);
            if (data.constructor === Array) {
                $scope.apps = data;
                console.log(data);
            }
        };

        $http(request).then(response);
    }

    $rootScope.menu = false;
    $rootScope.open = true;
    $rootScope.page = "Our Missions";
    $scope.id = $route.current.params.id;
    load();

    $scope.submit = function(e, mid, prog, value) {
        $rootScope.loading = true;
        const url = "site-resources/api/submit.php";
        const formData = new FormData();
        const files = e.target.files;
        formData.append('0', files[0]);
        formData.append('gid', $scope.id);
        formData.append('moid', mid);
        formData.append('value', value);
        formData.append('progression', prog);

        const response = function(res) {
            $rootScope.loading = false;
            const data = res.data;
            console.log(data);
            if (data !== "success") alert("Something went wrong.");
            else {
                load();
            }
        }

        $http({
            url: 'site-resources/api/submit_mission.php',
            method: "POST",
            data: formData,
            headers: {
                'Content-Type': undefined
            }
        }).then(response);
    }
}