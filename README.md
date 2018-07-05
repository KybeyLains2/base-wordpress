# Novo Projeto

```
git clone --depth 1 git@gitlab.com:jawsdigital/wpbase.git <pasta-projeto>
cd <pasta-projeto>
npm install
```

## Após instalação dos plugin por npm
```
npm audit fix (opcional)
```
Editar o arquivo package.json:
- themeName: Nome do tema, nome que será exibido no menu Aparência 
- name: Nome da pasta do tema, não pode haver letras maiúsculas, caracteres especiais e espaços
- version: Versão atual do tema do cliente

```
grunt start

git remote -v // Verifica o repositório remoto atual
git fetch --unshallow origin // Desvincula com o repositório da base
git remote set-url origin <git://github.com/example-user/example-repo.git>
git remote -v // Verifica se repositório remoto foi alterado
git add .
git commit -m "Iniciado repositório"
git push -u origin master
```

Configuração finalizada :) Seja feliz em seu desenvolvimento...


# Projeto existente
```
npm install
```

# Principais comandos GRUNT 
## Desenvolvimento local
```
grunt
```
## Homologação
```
grunt stage
```
## Produção
```
grunt prod
```

# Configuração para Homologação e Produção
Editar o arquivo sync.setup.json
```
{
	"stage": {
		"type": "",
		"dest": "",
		"host": "",
		"port": 21,
		"user": "",
		"pwd" : ""
	},
	"prod": {
		"type": "",
		"dest": "",
		"host": "",
		"port": 21,
		"user": "",
		"pwd" : ""
	}
}
```