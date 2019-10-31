<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016101300674229",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAztEyQ1pIYvrNCF8Ie4lNjjXEheY2K9GttcOWNpT575k+lrA0/K7GElrF1aorC/Mbgonbjvyzx3D92GzlbthccRjTpElyfdKjbZeLcyyuvKa50Qxo1pHWGkzfyK1w4dGYvyYhfiu+bwx7qpZgUHcwBHFzMZ8Hk/99SaiFE0OaJzPDSt/lChd65uPXLXPTLSuCUuWX54aV8AqTP+RNli94FM5nGa29NTArCsjiT0G4BDXR4EweiT3zGMiHvxYa5ZX0HB4eFMKWBLuoZo5n3qtDLusmR1Vz+OzAqKDpmsM8Ly0IIrpVRfael9I19b+GKXH3MiHon4V4l15YA5kbNoW6wwIDAQABAoIBAQCftIvL1vvu8x1BSaGTRAPPvVV00MEkNVae9doZbBFVJFFgq9E/DveFVPNQe4eiQqkLtMkHMbziVi6v4eeKISnBbBqC0vTGlNH+3vegiPCmXVlARQTwLM3YoWRt4wE4YFJDyX4zoDP+LPWSVW0qtd8ujy0VNGj1sGVfa/LXXWy8t6dbhIfudTjH2ntnzH79bRN/wDcnwazc69+wus6z42HN2/0ieTcQyNaNsMDBfr4UNSTAbP1orq77Y76iBGlYh3t+JALRvcUgdbr6d7U8jfPGtJYNXdoC2m4lpussTc+qbov6+99WwW7M5KAhg4qJpOxamDhNaBlCiRP4BRZw5OkBAoGBAPukYAhcK1R4ks8c34OHNF+fP/QM3qenPvtdYuIRuUFudalHmwjSGhTc+q60HB52S0s9n4bxOfCsTabVqk1T5ocxH3QcyvUGDNFmJezU8JfmofIUcpZUPiNMAuduQ994ySJr/tasNJvd0SfhJnzd0BHmPS06qRSDGx7CU++KFnS5AoGBANJmGGiaMELvZIVCzO6OWh2JtBe2wbnYVMvxlmKe/4hjgTwWl/UqgztgWniiB2m01HdPdp5x1W6+8JboRclgOrIW8gh4+dw/s1B8OfGrGVzARX9MHlD5KBgkGTBKerAyT9+tJGqD5ro3b/O+yYluW0oQYezRO2A4rACs3niIR6VbAoGAJAI3g7q8sI+4Yk69ZTLZub0uB5w32EHEb2/DkJn8qnJF//0xICgAd2/Pp2Q6idlfmNqukz69jcT6L59viTUyTbIn0BO2ry04dFZrouIts27bSarHYt+XLLHZ0IgkyjucfIJ7qvhlZtBs94nuWfvbpFQP+QLl4vuHy/T+0c4uvHECgYAFAhkipEjxzvD9mxF1vD245kY7KkaCPfwGvsFtp4s9m+C/pWoIk/J7v1dn97NleMB19pZEP0FtWW4wqWa/3GKJSrKIMRkBfSKvj8VxUzlPU+RTm2Q9WTDzIijXVW6GMyxjjqOHRYyAckFf8/KoKtBRewJZuMFJERBHE9ZX2CaccQKBgF1k3VRgg73QjgaOObqS5BrwX4juCc7j9TcNKz2IS2zqnFS2MhTYdLp5dzmJ9bkYJ/Jzrlx4ThCMwINZM3RcnPrp5KZt6ggl50J9UXNbQFpHIOA/sfKqtQaU3KdDTVaYe44/zcbbkAo0JC+ZitpTo4arM5BbyUfS5crIfjr1x7gi",
		
		//异步通知地址
		'notify_url' => "http://www.pyg.com/home/order/notify",
		
		//同步跳转
		'return_url' => "http://www.pyg.com/home/order/calloback",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
//		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",//测试
		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjN+kKttA1GZpyHDKbqUJwZb756iks7pvUvUb/FJmnIY5nxOMNeKooTmCq7Q++JDNXoE1oPHNiE7UBR05hT5u6oMk/MaVkQ5HlBEfu75+v+vN2Q+qTC2minnmTFgvG5f/qVFTLSUczYpeM5MCzybBHPn26HiTSjgluYTIMgD5FkmqwS0lAzN0rpQsFiGL7q0hqu88b2c+Fa9/tsr3trquI72ZKQ8dNW2n5bMN+5hPiRotdZLU1KW25hsrPlOP2ltbp/J9Ch2uVfvPe9FOlAbXROPoNkprlthAD4QQ/3XAfw5k0tlbCo2SOdA2XxKuVhQVBx5cnR/2GrSDk14iMQ2I5wIDAQAB",
);