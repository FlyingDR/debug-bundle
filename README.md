Debug CSRF bundle
=================

Small Symfony 2 bundle to disable CSRF token validation when running application under debugger.

Automatically detects that current request runs under debugger (Xdebug or Zend Debugger are recognized) and allows to replace CSRF token validation result with defined value.

