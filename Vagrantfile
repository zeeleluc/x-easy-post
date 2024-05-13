Vagrant.configure("2") do |config|
    config.vm.box = "jeffnoxon/ubuntu-20.04-arm64"
    config.vm.provider "parallels" do |v|
        v.name = "x-easy-post"
        v.memory = 2048
        v.cpus = 2
        v.linked_clone = false
    end
    config.vm.synced_folder ".", "/var/www", type: "nfs"

    config.vm.define "x-easy-post"
    config.vm.hostname = "x-easy-post"
    config.vm.network "private_network", ip: "192.168.56.22"

    config.vm.provision :shell, path: "provision/components/apache.sh"
    config.vm.provision :shell, path: "provision/components/php.sh"
    config.vm.provision :shell, path: "provision/components/mysql.sh"

    config.vm.provision "file", source: "provision/aliases", destination: "/tmp/bash_aliases"
    config.vm.provision "shell" do |s|
        s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_aliases > /home/vagrant/.bash_aliases"
    end

    config.vm.provision :shell, path: "provision/after.sh"
    config.vm.provision :shell, inline: "echo 'cd /var/www' >> /home/vagrant/.profile"
end
