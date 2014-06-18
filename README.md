Debug bundle
============

This bundle provides several services that are mean to simplify developing process of Symfony 2 applications by emulating certain security related features in a case if request is running under debugger.

Debugger detection
------------------
Debugger detection is handled by ```DebuggerDetectorListener```, **Xdebug** and **Zend Debugger** are recognized at this moment.

If some service needs to know if request is running under debugger - it should implement ```DebuggerStatusSubscriberInterface``` to get request status as soon as it will be determined.

CSRF token validation emulation
-------------------------------
When debugging form submissions - it may be useful to disable CSRF token validation under debugger while having CSRF validation enabled.

CSRF token validation emulation is controlled by configuration:
```yaml
debug:
    csrf:
        # true to enable CSRF token validation emulation, false to disable it completely
        enabled: true
        # Status of emulated CSRF token validation
        token_validation_status: true
```
Validation emulation is disabled automatically for production environment and can also be disabled in development environment. When enabled - it will substitute real CSRF validation with configured value if request was running under debugger. For normal requests all CSRF validation will be passed to real CSRF token manager.
