# vpc-boilerplate
VPC boilerplate for AWS Rockaway Hackathon

Configs are written in Neon in folder ./configs/**

Install dependencies
```
composer install
```

Convert to JSON
```
./cf.php convert configs/vpc-boilerplate.neon outputs/vpc-boilerplate.json
```

## Differences to Neon

### Includes
When value is defined like @filename.neon then content of filename.neon is inserted as value.

### Inheritance
not used here
