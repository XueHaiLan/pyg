<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2019092267726457",
		//编码格式
		'charset' => "UTF-8",
		//签名方式
		'sign_type'=>"RSA2",
		//scope auth_user获取用户信息; auth_base静默授权
		'scope' => 'auth_user',
		//跳转地址
		'redirect_url' => 'http://'.$_SERVER['HTTP_HOST'].'/home/login/alicallback',
        //支付宝授权地址 沙箱环境
//        'oauth_url' => 'https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm',
        //支付宝授权地址 正式环境
         'oauth_url' => 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm',
        //支付宝网关 沙箱环境
//        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
        //支付宝网关 正式环境
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//商户私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEA6uFopCX/BB6IpErsjYCoFx3hAv678WScLn+J8Bu/ISyL5BTzETwheg6EYYVuiG+TU3PRPhYec4N0TYG3cQQjCtKJdu6ECzBk+g4kHXTvjUU5zn1aUEg3AAStTBfWuwAchSBO980Z1C2T2O3If82LSwXyKVGzmuWoUWZn8PGOzUjFuo7ZVo24rO6+xYh+cABtKJPhHQ3VqM7FQ5gKv7t0Kj1Yig7RCTPjTEiGp4kzdelWKDJFo14AuAFX+QYzVGw+6uE3/Vk8g1xF4XAbed84oCmmbdFmk+b0jh9Lk8OJDLwrjB/wA0pUDZDwcvsAPBJc/YjFICOhtI9d1hSlmcHUQwIDAQABAoIBAQDqH9GcfbtbMcxmZ8Cvs+TZVhEqWRyiG/WKa9rlGeNEgBDZQwCe119PORU/2C87lGw4LSQPTSEBZ7EQaRKfds4vRXl8sXexZ4XfsyTQr/TwzmY2q8DQcYtRW1gal1CViiDjmjbczvbGBsBu5WaL7ijAmaavBR+QcQd8TCWjiEUd3VlOyovKkBOCu7TuT5qZmfEEdQMvO+puNukBtsf3aN7nnCBWadm9oaQ8pzsy1HvJsIczpMv8e7rP7ANywKL6QNnS/lOSzWt+imwjB5iR6/IYVKrEdeL0gJ0g8/Px7EbASc5sReLMdGnHqhbrs7abW1zoTGwaLUdl0pgq0aT9Ot7BAoGBAPZnvOR9bbdfXmR1EX1xnlYptOACL4axu7yRDGVFW2KzlHfsGPhOjsT4iR4GetvDQ6iIfcQEMkADFjpqFmzu0fV8SH38z3jiDiTVtEThwnJ5plUmfj5nXcVYkECJ/n2B1/JEl4EUjRhzLB6+4DVEYxcB1I3tLAkJrLAL9uIJbM9RAoGBAPQGybuE/R6czCGv/tWBgRvSboUw+Vd57QhNmQzK71CoBFWxdadqtipxZEIBT56gqJsiXbDGkluUT4qRl96OiAhhY734qlAIhOFH+YWYJknwWB6+vnbIoamocDLWis0XGK2Y3ti/MwX+Vu+68cUBuYoj8+cKnRzeOs4XMy9T241TAoGAFtwI6im4xApU93zk4n5n8hZscVvCjPxwLk5GmB6XG9ENNRLyPhcLp7pm/iIbw8eZv0jqYgUBpG6k4UZ2TiTZ1axyDT4nAU/3f/NThhBrubMPe1bW33M3f9d3ioVC1uH05sT2SUNe06Xbsf6DTm3Jw5in34LhUvVS0AJMJSw6rAECgYAWWxjYKRLXXx4EaA88mCSv29NsOFRfgzgH7rP6zAyoovvireNNdyVBNiotMdmNesJ3k+ppa4e1BB1VYIk9RmmH+wQcP0+7DUy/JszhRFMdqvsntenVEARfeqLsxWLp7xe/r6Wazclq3yYvyDymA8ZvtWN+4yYJowJYJx/1UgVnKQKBgQCgMeHgCg9+4V0Em/BlLikqazxpi19+gi+Ek1f/1qVoNhsK0xQnLJK44qaVUUQYH8JznneVSwE7KrZWrkX8Rpz766GrOZJ2495qnesyy+DjYI/Tps2CAyVp6J2c36bOPp+3scfX9aODJNWyRMK93gXZAhxVhHigbHaq4Y8a100QfA==",

);