const app = angular.module('app', ['ngAnimate', 'ngRoute']);

app.run(function($rootScope, $window, $timeout, AuthService) {
    $rootScope.version = '2.1.0';
    $rootScope.application = false;
    $rootScope.loading = false;
    $rootScope.logged = false;
    $rootScope.menu = true;
    $rootScope.page = "myPALBOTICS";
    $rootScope.open = false;
    $rootScope.back = function() {
        $rootScope.open = false;
        $timeout(function() {
            $rootScope.menu = true;
            $window.location.hash = "";
        }, 1000);
    }
    $rootScope.$on('$routeChangeStart', function(e, to) {
        const unrestricted = [''];
        const restricted = {
            'applications': 0,
            'mentors': 0,
            'programs': 0,
            'account': -1,
            'messages': -1,
            'team': 2,
            'vendors': 2
        };

        if (!to || unrestricted.indexOf(to.originalPath) >= 0) {
            return;
        }

        to.resolve = to.resolve || {};
        // can be overridden by route definition
        to.resolve.auth = to.resolve.auth || function(AuthService) {
            return AuthService(restricted[to.originalPath.split("/")[1]]);
        };
    });

    $rootScope.$on('$routeChangeError', function(event, current, previous, rejection) {
        if (rejection === 'Not Authenticated') {
            $window.location.hash = "";
        }
    })
});

app.config(function($routeProvider, $locationProvider, $httpProvider) {
    $routeProvider
        .when("/account", {
            templateUrl: "/site-resources/views/account.html",
            controller: "accountController"
        })
        .when("/applications/pending", {
            templateUrl: "/site-resources/views/pending_applications.html",
            controller: "pendingAppController"
        })
        .when("/applications/accepted", {
            templateUrl: "/site-resources/views/accepted_applications.html",
            controller: "acceptedAppController"
        })
        .when("/mentors/manage", {
            templateUrl: "/site-resources/views/mentors.html",
            controller: "mentorController"
        })
        .when("/mentors/:id/programs", {
            templateUrl: "/site-resources/views/assign-mentor-program.html",
            controller: "mentorProgramController"
        })
        .when("/programs", {
            templateUrl: "/site-resources/views/programs.html",
            controller: "programController"
        })
        .when("/programs/:id/manage", {
            templateUrl: "/site-resources/views/program-manage.html",
            controller: "programManageController"
        })
        .when("/programs/:id/manage/missions", {
            templateUrl: "/site-resources/views/create-mission.html",
            controller: "missionController"
        })
        .when("/programs/:id/manage/groups", {
            templateUrl: "/site-resources/views/program-groups.html",
            controller: "groupController"
        })
        .when("/mentors/:id/groups", {
            templateUrl: "/site-resources/views/assign-group.html",
            controller: "assignGroupController",
            role: "mentor"
        })
        .when("/participants/:id/groups", {
            templateUrl: "/site-resources/views/assign-group.html",
            controller: "assignGroupController",
            role: "participant"
        })
        .when("/team/manage", {
            templateUrl: "/site-resources/views/manage-team.html",
            controller: "manageTeamController"
        })
        .when("/team/:id/missions", {
            templateUrl: "/site-resources/views/view-missions.html",
            controller: "viewMissionController"
        })
        .when("/vendors", {
            templateUrl: "/site-resources/views/vendor-view.html",
            controller: function($scope, $rootScope) {
                $rootScope.menu = false;
                $scope.open = true;
                $rootScope.page = "Vendors";
            }
        })
        .when("/team/:id/awards", {
            templateUrl: "/site-resources/views/awards.html",
            controller: "awardTeamController"
        });
});

app.service('sharedProperties', function() {
    let property = {}

    return {
        getProperty: function(key) {
            return property[key];
        },
        setProperty: function(key, value) {
            property[key] = value;
        }
    }
});

app.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});