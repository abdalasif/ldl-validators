# LDL Framework Base Changelog

All changes to this project are documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [vx.x.x] - xxxx-xx-xx

### Added

- feature/1200379001241309 - Add factory method to ValidatorChainInterface, create generic ValidatorInterface collection
- feature/1200373038347529 - Add description to validator config / Add human validator dumper
- feature/1200240911349077 - Create OR & AND Validator chains, add "negate" capability in validator config, remove old generic ValidatorChain
- feature/1200200918895360 - Get succeeded, error and lastExecuted validators in ValidatorChain
- feature/1200117701811974 - Add more validators
- feature/1200112468837882 - ValidatorChain uses filter traits
- feature/1200099491334038 - Add more examples
- feature/1200099491334034 - Create initial validators, validator chain and examples
- feature/1200203297621230 - Add HasValidatorResultInterface
 
### Changed

- fix/1200323198982798 - Fix assertFalse on AndValidatorChain
- fix/1200266740213973 - Remove old validators, remove truncate from AbstractValidatorChain and his interface, Fix examples, Add strict to ClassComplianceValidatorConfig
- fix/1200240987751218 - Fix NumericComparisonValidator, use ComparisonHelper constants from base
- fix/1200159985965825 - Simplify configurations into BasicValidatorConfig

