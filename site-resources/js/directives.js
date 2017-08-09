app.directive('header', function () {
    return {
        templateUrl: 'site-resources/views/components/header.html',
    };
});

app.directive('footer', function () {
    return {
        templateUrl: 'site-resources/views/components/footer.html'
    };
});

app.directive('mcon', function () {
    return {
        template: `<div class ="modals-container"><div class="filter-page" style="display:none"></div></div>`,
    };
});

app.directive('messages', function () {
    return {
        template: `<div class="error">{{error}}</div>
            <div class="good">{{good}}</div>`,
    };
});

app.directive('login', function () {
    return {
        template: `<div class="opener" ng-controller="loginController" ng-hide="logged">
          <div class="verticle-middle">
              <div class="title verticle-middle">
                  <span class="info" ng-bind-html="message | unsafe"></span>
                  <br>
                  <input type="{{type}}" placeholder="{{placeholder}}" ng-model="username" ng-change="isUsername()" />
                  <button type="submit" ng-show="checked" ng-click="checkForm()">Login</button>
                  <div id = 'password-reset' ng-show="passed" ng-click="forgotPassword(user)">Forgot your password?</div>
                  <messages></messages>
              </div>
          </div>
        </div>`,
    };
});

app.directive('menu', function () {
    return {
        scope: {
            role: '@role'
        },
        templateUrl: 'site-resources/views/components/menu.html',
        controller: 'menuController'
    }
});
