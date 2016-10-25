[![Build Status](https://travis-ci.org/reinder83/binary-flags.svg?branch=master)](https://travis-ci.org/reinder83/binary-flags)

# BinaryFlags
With this class you can easily add flags to your models.
  
The number of flags you can use is limited to the architecture of your system, e.g.: 32 flags on a 32-bit system or 64 flags on 64-bit system. 
To store 64-bits flags in a database, you will need to store it as UNSIGNED BIGINT in MySQL or an equivalant in your datastorage.
 
## Example usage
