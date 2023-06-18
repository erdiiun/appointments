# Randevu Assessments
Gelen mailleri ilgili servisler aracılığıyla iletmek için geliştirilmiştir. Mailjet, SMTP ve default(Elastic) servisleri
ile mailler gönderilir. Mailjet ve SMTP ile gönderim başarısız olursa, default servis ile tekrardan gönderilir ve response
döner.

### Kurulum ve Gereksinimler
Proje Docker kullanılarak geliştirilmiştir. Öncelikle bilgisayrınıza Docker kurulumu yapmanız gerekmektedir.

```
git init
git clone https://github.com/erdiiun/appointments.git
```
Daha sonra ana dizinde,
`
docker compose up
`
ile projeyi başlatın.

`docker ps`

komutu ile çalışan Imagelerin containe idlerini listeleyiniz. Çalışan php containe id ile,

`docker exec -it 'containe_id' bash `

komutunu çalıştırın.

Proje dosyasına girdikten sonra,

`composer install`

komutunu çalıştırınız. 

Proje çalıştıktan sonra,

`php artisan migrate` ve `php artisan db:seed` ile veritabanı kurulumu ve veri girişlerini yapabilirsiniz.


### Proje Bilgileri

Proje, bir kullanıcı kayıt olduktan sonra giriş yaptığında bir token döner. Bu tokenı diğer servislerde authorization
Bearer olarak kullanmanız gerekmektedir. Token süresi 1 saattir.

Randevu oluşturmak için şirketleri listeleyebilirsiniz, listlenen şirketlerin çalışma saatlerini ve verdikleri hizmetleri
listeyebilirsiniz. Daha sonra ilgili şirket için bir randevu isteği oluşturabilirsiniz. Burada şirket çalışma saatleri
içerisinde olması gerekmektedir. Yine aynı şekilde randevu almak istediğiniz saatlerde başka randevu olmamsı gerekmektedir.

Randevu istediğinde bulunurken birden fazla hizmet için istekte bulunabilirsiniz. Burada hizmetleri ',' ile ayırmanız
gerekmektedir. Toplam hizmet süresi içerisinde başka bir randevu ile çakışması durumunda randevu verilmeyecektir.