# LDL Framework Base Changelog

All changes to this project are documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [vx.x.x] - xxxx-xx-xx

### Added

- feature/1200713875634271 - Improve performance on validator collection
- feature/1200707119795755 - Add ValidatorChainItemCollection
- feature/1200662492262010 - Add filters traits to ValidatorCollection
- feature/1200590689592046 - Apply decorator pattern for each validator added into a validator chain through the ValidatorChainItem class
- feature/1200577334210948 - Move description from config to validators itself
- feature/1200573159945611 - Add config interface and trait for validators
- feature/1200435476702581 - Add ResetValidatorInterface
- feature/1200427327628889 - Add a negated interface for validators
- feature/1200379001241309 - Add factory method to ValidatorChainInterface, create generic ValidatorInterface collection
- feature/1200373038347529 - Add description to validator config / Add human validator dumper
- feature/1200240911349077 - Create OR & AND Validator chains, add "negate" capability in validator config, remove old generic ValidatorChain
- feature/1200200918895360 - Get succeeded, error and lastExecuted validators in ValidatorChain
- feature/1200117701811974 - Add more validators
- feature/1200112468837882 - ValidatorChain uses filter traits
- feature/1200099491334038 - Add more examples
- feature/1200099491334034 - Create initial validators, validator chain and examples
- feature/1200203297621230 - Add HasValidatorResultInterface
- feature/1200836379007029 - Add StringLengthValidator / StringContainsValidator
- feature/1201055143148093 - Add example outputs. Delete run.php
 
### Changed

- fix/1200641806243439 - Fix getCollection from AbstractValidatorChain
- fix/1200636888436614 - Fix unshift from AbstractValidatorChain
- fix/1200626063657123 - Remove validators config
- fix/1200492312944670 - Fix AbstractValidatorChain
- fix/1200410494797360 - Fix getCollection and add UnshiftInterfaceTrait on AbstractValidatorChain
- fix/1200323198982798 - Fix assertFalse on AndValidatorChain
- fix/1200266740213973 - Remove old validators, remove truncate from AbstractValidatorChain + interface, Fix examples, Add strict to ClassComplianceValidatorConfig
- fix/1200240987751218 - Fix NumericComparisonValidator, use ComparisonHelper constants from base
- fix/1200159985965825 - Simplify configurations into BasicValidatorConfig
- fix/1200946118981404 - Small enhancements to comply with changes done in base
- fix/1200949376247320 - Incorporate separation of Remove interfaces from base lib 
- fix/1201030058026870 - Change ReplaceEqualValueInterfaceTrait to ReplaceByValueInterfaceTrait
