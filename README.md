# WPGraphQL Login and Logout

This plugin adds support for login and logout using wordpress and cookies.

## Credits

From https://mikejolley.com/2021/03/02/headless-wordpress-cookie-based-login-using-graphql/

## Activating / Using
Activate the plugin like you would any other WordPress plugin. 

This plugin adds two mutations for graphql.

```
mutation login {
  login(input: { login: "postlight", password: "postlight" }) {
    status
  }
}

mutation logout {
  logout(input: {}) {
    status
  }
}
```

## Known Issues

### None at this time